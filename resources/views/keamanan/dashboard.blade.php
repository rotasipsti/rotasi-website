@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">


    <!-- Dashboard Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold">Dashboard Keamanan</h1>
            <p class="text-muted-foreground mt-1">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span> 👋</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8 mt-6">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium uppercase tracking-wider text-muted-foreground">Total Izin</h3>
                <i data-lucide="history" class="h-4 w-4 text-green-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_exit ?? 0 }}</div>
                <p class="text-xs text-muted-foreground mt-1">Riwayat izin keseluruhan</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium uppercase tracking-wider text-muted-foreground">Izin Hari Ini</h3>
                <i data-lucide="calendar-clock" class="h-4 w-4 text-purple-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $today_exit ?? 0 }}</div>
                <p class="text-xs text-muted-foreground mt-1">Perizinan keluar hari ini</p>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('keamanan.exit.scanner') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="scan-line" class="h-5 w-5 text-blue-600"></i>
                </div>
                <span class="text-sm font-medium">Scanner Izin Keluar</span>
            </a>
            
            <a href="{{ route('keamanan.exit.history') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="history" class="h-5 w-5 text-orange-600"></i>
                </div>
                <span class="text-sm font-medium">Riwayat Izin</span>
            </a>
        </div>
    </div>
</div>
@endsection
