@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Riwayat Task: {{ $task->title }}</h1>

    <div class="bg-white shadow rounded p-4">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                    <th class="px-4 py-2 text-left">Dilakukan Oleh</th>
                    <th class="px-4 py-2 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $h)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $h->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-2">{{ ucfirst($h->action) }}</td>
                        <td class="px-4 py-2">{{ $h->actionBy->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $h->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
