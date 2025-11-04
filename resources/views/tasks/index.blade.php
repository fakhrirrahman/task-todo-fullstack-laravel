@extends('layouts.app')

@section('content')
    @php
        use App\Models\Task;
    @endphp

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4">Daftar Task</h1>

        {{-- Tombol tambah task, hanya untuk Pelaksana --}}
        @if ($user && $user->hasRole('pelaksana'))
            <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Buat Task Baru
            </a>
        @endif

        <table class="w-full mt-6 border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Judul</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Progress</th>
                    <th class="p-2 border">Leader</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td class="p-2 border">{{ $task->title }}</td>
                        <td class="p-2 border">{{ $task->status }}</td>
                        <td class="p-2 border">{{ $task->progress }}%</td>
                        <td class="p-2 border">{{ $task->leader?->name ?? '-' }}</td>
                        <td class="p-2 border space-x-2">

                            {{-- Edit: hanya Pelaksana yang buat task dan sedang direvisi --}}
                            @if (
                                $user &&
                                    $user->hasRole('pelaksana') &&
                                    $task->status === Task::STATUS['Revision'] &&
                                    $task->created_by === $user->id)
                                <a href="{{ route('tasks.edit', $task->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                            @endif

                            {{-- Review: hanya Leader yang ditugaskan --}}
                            @if (
                                $user &&
                                    $user->hasRole('leader') &&
                                    in_array($task->status, [Task::STATUS['Submitted'], Task::STATUS['Revision']]) &&
                                    $task->assigned_leader === $user->id)
                                <a href="{{ route('tasks.review', $task->id) }}"
                                    class="text-yellow-600 hover:underline">Review</a>
                            @endif

                            {{-- Update Progress: hanya Pelaksana yang buat task --}}
                            @if (
                                $user &&
                                    $user->hasRole('pelaksana') &&
                                    $task->status === Task::STATUS['Approve by Leader'] &&
                                    $task->created_by === $user->id)
                                <a href="{{ route('tasks.progress', $task->id) }}"
                                    class="text-green-600 hover:underline">Progress</a>
                            @endif

                            {{-- Override progress: hanya Leader yang ditugaskan --}}
                            @if (
                                $user &&
                                    $user->hasRole('leader') &&
                                    $task->status === Task::STATUS['In Progress'] &&
                                    $task->assigned_leader === $user->id)
                                <a href="{{ route('tasks.override', $task->id) }}"
                                    class="text-indigo-600 hover:underline">Override</a>
                            @endif

                            {{-- Complete: Pelaksana atau Leader boleh menyelesaikan bila progress == 100 --}}
                            @if (
                                $task->progress == 100 &&
                                    $user &&
                                    (($user->hasRole('pelaksana') && $task->created_by === $user->id) ||
                                        ($user->hasRole('leader') && $task->assigned_leader === $user->id)))
                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-gray-800 hover:underline">Selesaikan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">Belum ada task</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
