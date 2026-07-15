
@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
        
        



        <!-- Role specific content -->
<!-- Peserta Dashboard Content -->
    <x-dashboard-banner role="peserta" />
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold uppercase">Dashboard Peserta</h1>
            <p class="text-muted-foreground">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span> 👋</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:shadow-md transition-shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Total Tugas</h3>
                <i data-lucide="file-text" class="h-4 w-4 text-muted-foreground"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ count($tasks) }}</div>
                <p class="text-xs text-muted-foreground">Sektor {{ auth()->user()->sektor }}</p>
            </div>
        </div>
        
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:shadow-md transition-shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Tugas Dikumpulkan</h3>
                <i data-lucide="upload" class="h-4 w-4 text-muted-foreground"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">
                    {{ $submissions->whereIn('status', ['submitted', 'evaluated'])->count() }}
                </div>
                <p class="text-xs text-muted-foreground">dari total tugas</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:shadow-md transition-shadow sm:col-span-2 lg:col-span-1">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Mentor</h3>
                <i data-lucide="graduation-cap" class="h-4 w-4 text-muted-foreground"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-lg font-semibold truncate">
                    {{ $mentor ? $mentor->name : 'Belum ditentukan' }}
                </div>
                <p class="text-xs text-muted-foreground">Sektor {{ auth()->user()->sektor }}</p>
            </div>
        </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <a href="{{ route('peserta.tasks') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="file-text" class="h-5 w-5 text-blue-600"></i>
                </div>
                <span class="text-sm font-medium">Lihat Tugas</span>
            </a>
            
            <a href="{{ route('peserta.submissions') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="upload" class="h-5 w-5 text-green-600"></i>
                </div>
                <span class="text-sm font-medium">Pengumpulan</span>
            </a>

            <a href="{{ route('peserta.downloads') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="download" class="h-5 w-5 text-purple-600"></i>
                </div>
                <span class="text-sm font-medium">Unduh Materi</span>
            </a>
        </div>
    </div>

</div>
    </div>
@endsection
