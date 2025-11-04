@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-md">
        <h1 class="text-xl font-semibold mb-4">Review Task: {{ $task->title }}</h1>

        <form action="{{ route('tasks.review', $task->id) }}" method="POST">
            @csrf
            <p class="mb-3">Status saat ini: <strong>{{ $task->status }}</strong></p>

            <button name="action" value="approve" class="bg-green-600 text-white px-4 py-2 rounded">Approve</button>
            <button name="action" value="revise" class="bg-yellow-500 text-white px-4 py-2 rounded">Revisi</button>
        </form>
    </div>
@endsection
