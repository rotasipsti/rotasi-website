@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Dashboard Stakeholder</h1>
            <p class="text-muted-foreground">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span> 👋</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Total Tugas Acara</h3>
                <i data-lucide="file-text" class="h-4 w-4 text-indigo-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_tasks ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Tugas yang dibuat</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Data Pengumpulan</h3>
                <i data-lucide="upload-cloud" class="h-4 w-4 text-green-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_submissions ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Pengumpulan tersubmit</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Riwayat Izin</h3>
                <i data-lucide="history" class="h-4 w-4 text-orange-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_permissions ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Total izin keluar panitia & peserta</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow col-span-1">
            <div class="p-6 pb-4 border-b border-border/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-semibold text-lg">Pengumpulan Tugas Terbaru</h3>
                    <p class="text-sm text-muted-foreground">Aktivitas pengumpulan peserta terbaru.</p>
                </div>
                <a href="{{ route('stakeholder.submissions') }}" class="text-sm font-medium text-primary hover:underline">Lihat Semua</a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-muted text-muted-foreground">
                            <tr>
                                <th class="px-6 py-4">Tugas</th>
                                <th class="px-6 py-4">Peserta</th>
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @forelse($recent_submissions as $sub)
                            <tr class="hover:bg-muted/50 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $sub->task->title }}</td>
                                <td class="px-6 py-4">
                                    {{ $sub->participant->name }}
                                    <div class="text-xs text-muted-foreground">
                                        {{ $sub->participant->sektor ? 'Sektor ' . $sub->participant->sektor : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if($sub->submitted_at > $sub->task->due_date)
                                        <span class="bg-destructive/10 text-destructive text-xs font-medium px-2.5 py-0.5 rounded-full border border-destructive/20">Terlambat</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-green-400">Tepat Waktu</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-muted-foreground">Belum ada pengumpulan tugas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
