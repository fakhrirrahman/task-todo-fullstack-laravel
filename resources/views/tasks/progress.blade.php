@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-md">
        <h1 class="text-xl font-semibold mb-4">Update Progress: {{ $task->title }}</h1>

        <form action="{{ route('tasks.progress', $task->id) }}" method="POST">
            @csrf
            <div>
                <label class="block mb-2">Progress (%)</label>
                <input type="number" name="progress" min="0" max="100" class="border p-2 w-full rounded"
                    value="{{ $task->progress }}">
            </div>

            <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection
