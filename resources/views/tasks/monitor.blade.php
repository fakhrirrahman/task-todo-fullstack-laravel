@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4">Monitoring Task</h1>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Judul</th>
                    <th class="p-2 border">Progress</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Pelaksana</th>
                    <th class="p-2 border">Leader</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="border p-2">{{ $task->title }}</td>
                        <td class="border p-2">{{ $task->progress }}%</td>
                        <td class="border p-2">{{ $task->status }}</td>
                        <td class="border p-2">{{ $task->creator?->name ?? '-' }}</td>
                        <td class="border p-2">{{ $task->leader?->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
