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
                <input type="date" name="deadline" class="border p-2 w-full rounded"
                    value="{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->toDateString() : '' }}" required>
            </div>

            <div>
                <label class="block mb-1">Pilih Leader</label>
                <select name="assigned_leader" class="border p-2 w-full rounded" required>
                    <option value="">-- Pilih Leader --</option>
                    @foreach ($leaders as $leader)
                        <option value="{{ $leader->id }}" @if ($task->assigned_leader == $leader->id) selected @endif>
                            {{ $leader->name }}</option>
                    @endforeach
                </select>
                @error('assigned_leader')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Ulang</button>
        </form>
    </div>
@endsection
