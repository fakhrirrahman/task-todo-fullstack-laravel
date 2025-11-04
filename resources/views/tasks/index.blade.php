@extends('layouts.app')

@section('content')
    @php
        use App\Models\Task;
    @endphp

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4">Daftar Task</h1>

        @if ($user && $user->hasRole('pelaksana'))
            <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Buat Task Baru
            </a>
        @endif

        <div class="grid gap-6 mt-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($tasks as $task)
                @php
                    // status badge color
                    $badge = match ($task->status) {
                        Task::STATUS['Submitted'] => 'bg-blue-100 text-blue-800',
                        Task::STATUS['Revision'] => 'bg-yellow-100 text-yellow-800',
                        Task::STATUS['Approve by Leader'] => 'bg-indigo-100 text-indigo-800',
                        Task::STATUS['In Progress'] => 'bg-green-100 text-green-800',
                        Task::STATUS['Completed'] => 'bg-gray-100 text-gray-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 100) }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium {{ $badge }}">{{ $task->status }}</span>
                            <div class="text-xs text-gray-400 mt-1">Leader: {{ $task->leader?->name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm text-gray-600">Progress</div>
                            <div class="text-sm font-medium text-gray-700">{{ $task->progress }}%</div>
                        </div>
                        <div class="w-full bg-gray-100 h-3 rounded overflow-hidden">
                            <div class="h-3 bg-gradient-to-r from-green-400 to-green-600"
                                style="width: {{ $task->progress }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <a href="{{ route('tasks.history', $task->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-50 border rounded text-sm text-gray-700 hover:bg-gray-100">History</a>

                        @if (
                            $user &&
                                $user->hasRole('pelaksana') &&
                                $task->status === Task::STATUS['Revision'] &&
                                $task->created_by === $user->id)
                            <a href="{{ route('tasks.edit', $task->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 border rounded text-sm text-blue-600 hover:bg-blue-100">Edit</a>
                        @endif

                        @if (
                            $user &&
                                $user->hasRole('leader') &&
                                in_array($task->status, [Task::STATUS['Submitted'], Task::STATUS['Revision']]) &&
                                $task->assigned_leader === $user->id)
                            <a href="{{ route('tasks.review', $task->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-50 border rounded text-sm text-yellow-700 hover:bg-yellow-100">Review</a>
                        @endif

                        @if (
                            $user &&
                                $user->hasRole('pelaksana') &&
                                in_array($task->status, [Task::STATUS['Approve by Leader'], Task::STATUS['In Progress']]) &&
                                $task->created_by === $user->id)
                            <a href="{{ route('tasks.progress', $task->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-green-50 border rounded text-sm text-green-700 hover:bg-green-100">Progress</a>
                        @endif

                        @if (
                            $user &&
                                $user->hasRole('leader') &&
                                $task->status === Task::STATUS['In Progress'] &&
                                $task->assigned_leader === $user->id)
                            <a href="{{ route('tasks.override', $task->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 border rounded text-sm text-indigo-700 hover:bg-indigo-100">Override</a>
                        @endif

                        @if (
                            $task->progress == 100 &&
                                $user &&
                                (($user->hasRole('pelaksana') && $task->created_by === $user->id) ||
                                    ($user->hasRole('leader') && $task->assigned_leader === $user->id)))
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="inline">
                                @csrf
                                <button
                                    class="px-3 py-1.5 bg-gray-800 text-white rounded text-sm hover:bg-black">Selesaikan</button>
                            </form>
                        @endif

                        @if (
                            $user &&
                                $user->hasRole('pelaksana') &&
                                $task->created_by === $user->id &&
                                $task->status !== Task::STATUS['Approve by Leader']
                        )
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus task ini?');">
                                @csrf
                                <button
                                    class="px-3 py-1.5 bg-red-50 text-red-700 border rounded text-sm hover:bg-red-100">Hapus</button>
                            </form>
                        @endif

                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">Belum ada task</div>
            @endforelse
        </div>
    </div>
@endsection
