<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Daftar semua task (untuk semua role)
    public function index()
    {
        $user = Auth::user();
        $tasks = Task::with(['creator', 'leader', 'progressBy'])->latest()->get();

        return view('tasks.index', compact('tasks', 'user'));
    }

    // Halaman form create task (Pelaksana)
    public function create()
    {
        $user = Auth::user();

        if (!$user->hasRole('pelaksana')) {
            abort(403, 'Hanya Pelaksana yang bisa membuat task.');
        }

        $leaders = User::role('leader')->get();
        return view('tasks.create', compact('leaders'));
    }

    // Simpan task baru
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('pelaksana')) {
            abort(403, 'Hanya Pelaksana yang bisa submit task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_leader' => 'required|exists:users,id',
            'deadline' => 'required|date',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_leader' => $request->assigned_leader,
            'created_by' => $user->id,
            'status' => Task::STATUS['Submitted'],
            'progress' => 0,
            'deadline' => $request->deadline,
        ]);

        // ✅ Catat history submit
        TaskHistory::create([
            'task_id' => $task->id,
            'action_by' => $user->id,
            'action' => TaskHistory::ACTIONS['submit'],
            'note' => 'Task baru dibuat dan dikirim ke Leader.',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dikirim.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if (
            !$user->hasRole('pelaksana') ||
            $task->created_by != $user->id ||
            $task->status != Task::STATUS['Revision']
        ) {
            abort(403, 'Hanya Pelaksana dari task ini yang bisa mengedit pada status Revision.');
        }

        $leaders = User::role('leader')->get();
        return view('tasks.edit', compact('task', 'leaders'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if (
            !$user->hasRole('pelaksana') ||
            $task->created_by != $user->id ||
            $task->status != Task::STATUS['Revision']
        ) {
            abort(403, 'Hanya Pelaksana dari task ini yang bisa mengupdate pada status Revision.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_leader' => 'required|exists:users,id',
            'deadline' => 'required|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_leader' => $request->assigned_leader,
            'deadline' => $request->deadline,
            'status' => Task::STATUS['Submitted'],
        ]);

        // ✅ Catat history update
        TaskHistory::create([
            'task_id' => $task->id,
            'action_by' => $user->id,
            'action' => TaskHistory::ACTIONS['submit'],
            'note' => 'Task diperbarui dan dikirim ulang ke Leader.',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil diperbarui dan dikirim ulang.');
    }

    // Leader review task
    public function review(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('leader')) {
            abort(403, 'Hanya Leader yang bisa review task.');
        }

        $task = Task::findOrFail($id);
        if ($task->assigned_leader != $user->id) {
            abort(403, 'Anda bukan leader dari task ini.');
        }

        if ($request->isMethod('get')) {
            return view('tasks.review', compact('task'));
        }

        $request->validate(['action' => 'required|in:approve,revise']);

        if ($request->action === 'approve') {
            $task->update(['status' => Task::STATUS['Approve by Leader']]);

            // ✅ Catat history approve
            TaskHistory::create([
                'task_id' => $task->id,
                'action_by' => $user->id,
                'action' => TaskHistory::ACTIONS['approve'],
                'note' => 'Task disetujui oleh Leader.',
            ]);
        } else {
            $task->update(['status' => Task::STATUS['Revision']]);

            // ✅ Catat history revision
            TaskHistory::create([
                'task_id' => $task->id,
                'action_by' => $user->id,
                'action' => TaskHistory::ACTIONS['revision'],
                'note' => 'Task dikembalikan ke Pelaksana untuk revisi.',
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task berhasil direview.');
    }

    // Pelaksana update progress
    public function progress(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if (!$user->hasRole('pelaksana') || $task->created_by != $user->id) {
            abort(403, 'Hanya pelaksana task ini yang bisa mengupdate progress.');
        }

        // hanya boleh update progress setelah Leader menyetujui (Approve by Leader)
        if (!in_array($task->status, [Task::STATUS['Approve by Leader'], Task::STATUS['In Progress']])) {
            abort(403, 'Task harus disetujui oleh Leader sebelum Pelaksana mengupdate progress.');
        }

        if ($request->isMethod('get')) {
            return view('tasks.progress', compact('task'));
        }

        $request->validate(['progress' => 'required|integer|min:0|max:100']);
        $task->update([
            'progress' => $request->progress,
            'status' => $request->progress >= 100 ? Task::STATUS['Completed'] : Task::STATUS['In Progress'],
            'progress_by' => $user->id,
        ]);

        // ✅ Catat history update progress
        TaskHistory::create([
            'task_id' => $task->id,
            'action_by' => $user->id,
            'action' => TaskHistory::ACTIONS['update progress'],
            'note' => 'Progress diperbarui menjadi ' . $request->progress . '% oleh Pelaksana.',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Progress diperbarui.');
    }

    // Leader koreksi progress
    public function override(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if (!$user->hasRole('leader') || $task->assigned_leader != $user->id) {
            abort(403, 'Hanya leader task ini yang bisa koreksi progress.');
        }

        if ($request->isMethod('get')) {
            return view('tasks.override', compact('task'));
        }

        $request->validate(['progress' => 'required|integer|min:0|max:100']);

        $oldProgress = $task->progress;
        $task->update([
            'progress' => $request->progress,
            'progress_by' => $user->id,
            'status' => $request->progress >= 100 ? Task::STATUS['Completed'] : Task::STATUS['In Progress'],
        ]);

        // ✅ Catat history koreksi progress
        TaskHistory::create([
            'task_id' => $task->id,
            'action_by' => $user->id,
            'action' => TaskHistory::ACTIONS['update progress'],
            'note' => "Leader mengoreksi progress dari {$oldProgress}% menjadi {$request->progress}%",
        ]);

        return redirect()->route('tasks.index')->with('success', 'Progress dikoreksi.');
    }

    // Selesaikan task
    public function complete($id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if (!(
            ($user->hasRole('pelaksana') && $task->created_by == $user->id) ||
            ($user->hasRole('leader') && $task->assigned_leader == $user->id)
        )) {
            abort(403, 'Hanya pelaksana atau leader yang bisa menyelesaikan task.');
        }

        $task->update([
            'status' => Task::STATUS['Completed'],
            'progress' => 100,
            'progress_by' => $user->id,
        ]);

        // ✅ Catat history complete
        TaskHistory::create([
            'task_id' => $task->id,
            'action_by' => $user->id,
            'action' => TaskHistory::ACTIONS['complete'],
            'note' => 'Task diselesaikan sepenuhnya.',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task diselesaikan.');
    }

    // Manager monitor semua task
    public function monitor()
    {
        $user = Auth::user();

        if (!$user->hasRole('manager')) {
            abort(403, 'Hanya Manager yang bisa memonitor task.');
        }
        // Manager hanya melihat task yang sudah disetujui oleh Leader
        $tasks = Task::with(['creator', 'leader', 'progressBy'])
            ->where('status', Task::STATUS['Approve by Leader'])
            ->latest()
            ->get();
        return view('tasks.monitor', compact('tasks'));
    }

    public function history($id)
    {
        $task = Task::findOrFail($id);
        $histories = TaskHistory::with('actionBy')->where('task_id', $id)->latest()->get();

        return view('tasks.history', compact('task', 'histories'));
    }
}
