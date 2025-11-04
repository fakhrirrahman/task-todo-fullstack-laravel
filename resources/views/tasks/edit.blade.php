@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-lg">
        <h1 class="text-2xl font-semibold mb-4">Revisi Task</h1>

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1">Judul Task</label>
                <input type="text" name="title" class="border p-2 w-full rounded" value="{{ $task->title }}" required>
            </div>

            <div>
                <label class="block mb-1">Deskripsi</label>
                <textarea name="description" class="border p-2 w-full rounded">{{ $task->description }}</textarea>
            </div>

            <div>
                <label class="block mb-1">Deadline</label>
                <input type="date" name="deadline" class="border p-2 w-full rounded" value="{{ $task->deadline }}"
                    required>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Ulang</button>
        </form>
    </div>
@endsection
