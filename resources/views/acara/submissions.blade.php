@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Data Pengumpulan Tugas</h1>
        <p class="text-muted-foreground">Detail seluruh peserta yang mengumpulkan tugas berdasarkan sektor</p>
    </div>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-muted-foreground bg-muted uppercase border-b">
                    <tr>
                        <th class="px-6 py-4">Sektor</th>
                        <th class="px-6 py-4">Peserta</th>
                        <th class="px-6 py-4">Tugas</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Pengumpulan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr class="border-b last:border-0 hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-4 font-semibold">
                                Sektor {{ $sub->participant->sektor ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $sub->participant->name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $sub->participant->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-primary">{{ $sub->task->title }}</div>
                                <div class="text-xs text-muted-foreground">
                                    {{ ucfirst(str_replace('_', ' ', $sub->task->task_type)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $sub->status === 'evaluated' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $sub->submitted_at->format('d M Y, H:i') }}</span>
                                    @if($sub->submitted_at > $sub->task->due_date)
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 w-fit">
                                            Terlambat
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sub->file_url)
                                    <a href="{{ $sub->file_url }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4">
                                        <i data-lucide="download" class="mr-2 h-4 w-4"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-xs text-muted-foreground italic">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-12 rounded-xl border-none bg-transparent text-muted-foreground">
                                    <i data-lucide="inbox" class="mx-auto h-12 w-12 mb-4 opacity-50"></i>
                                    <p>Belum ada data pengumpulan tugas dari peserta.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
