
@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
        
        



        <!-- Acara Dashboard Content -->
        <x-dashboard-banner role="acara" />
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold uppercase">Dashboard Acara</h1>
                <p class="text-muted-foreground">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span> 👋</p>
            </div>
        </div>
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8">
            <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Total Tugas</h3>
                    <i data-lucide="file-text" class="h-4 w-4 text-muted-foreground"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold">{{ count($tasks) }}</div>
                    <p class="text-xs text-muted-foreground">Keseluruhan tugas dibuat</p>
                </div>
            </div>
            
            <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Submission Terbaru</h3>
                    <i data-lucide="upload" class="h-4 w-4 text-muted-foreground"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold">
                        {{ count($recent_submissions) }}
                    </div>
                    <p class="text-xs text-muted-foreground">Aktifitas terbaru</p>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('acara.tasks') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="file-text" class="h-5 w-5 text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium">Manajemen Tugas</span>
                </a>
                
                <a href="{{ route('acara.submissions') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="check-square" class="h-5 w-5 text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium">Data Pengumpulan</span>
                </a>
            </div>
        </div>
        <!-- Recent Submissions -->
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="p-6">
                <div class="flex items-center gap-2 text-lg font-semibold mb-1">
                    <i data-lucide="activity" class="h-5 w-5"></i>
                    Aktivitas Pengumpulan Terkini
                </div>
                <p class="text-sm text-muted-foreground mb-6">10 pengumpulan tugas terakhir oleh peserta</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground bg-muted uppercase border-b">
                            <tr>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Peserta</th>
                                <th class="px-4 py-3">Tugas</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_submissions as $sub)
                                <tr class="border-b">
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $sub->submitted_at->diffForHumans() }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $sub->participant->name }} (Sektor {{ $sub->participant->sektor }})</td>
                                    <td class="px-4 py-3">{{ $sub->task->title }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $isLate = $sub->submitted_at > $sub->task->due_date;
                                            $statusText = $isLate ? 'Terlambat' : ucfirst($sub->status);
                                            
                                            if ($isLate) {
                                                $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800';
                                            } elseif ($sub->status === 'evaluated') {
                                                $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800';
                                            } else {
                                                $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">Belum ada aktivitas pengumpulan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    
    </div>
</div>
@endsection
