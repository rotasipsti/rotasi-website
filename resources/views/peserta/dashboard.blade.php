
@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
        
        



        <!-- Role specific content -->
<!-- Peserta Dashboard Content -->
@if(is_null(auth()->user()->sektor))
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
        <div class="h-20 w-20 bg-primary/10 rounded-full flex items-center justify-center mb-6">
            <i data-lucide="shield-alert" class="h-10 w-10 text-primary"></i>
        </div>
        <h2 class="text-2xl font-bold mb-2">Pilih Sektor Anda</h2>
        <p class="text-muted-foreground mb-8 max-w-md">Untuk melanjutkan dan mengakses fitur dashboard, silakan pilih sektor Anda dan masukkan password sektor yang telah diberikan.</p>
        
        <div class="bg-card border border-border/50 rounded-xl shadow-sm p-6 w-full max-w-md text-left">
            <form action="{{ route('peserta.select-sector') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="sektor" class="text-sm font-medium">Pilih Sektor</label>
                        <select id="sektor" name="sektor" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-2" required>
                            <option value="" disabled selected>Pilih sektor</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">Sektor {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div x-data="{ showPassword: false }">
                        <label for="sectorPassword" class="text-sm font-medium">Password Sektor</label>
                        <div class="relative mt-2">
                            <input id="sectorPassword" :type="showPassword ? 'text' : 'password'" name="sectorPassword" placeholder="Masukkan password sektor" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm pr-10" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-0 top-0 h-full px-3 py-2 text-muted-foreground flex items-center justify-center">
                                <i data-lucide="eye-off" class="h-4 w-4" x-show="showPassword" style="display: none;"></i>
                                <i data-lucide="eye" class="h-4 w-4" x-show="!showPassword"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md text-sm font-medium mt-6">
                        Simpan & Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>

@elseif(!auth()->user()->is_approved)
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
        <div class="h-20 w-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-6">
            <i data-lucide="clock" class="h-10 w-10 text-amber-600 dark:text-amber-500"></i>
        </div>
        <h2 class="text-2xl font-bold mb-2">Menunggu Persetujuan</h2>
        <p class="text-muted-foreground mb-4 max-w-md">Akun Anda sedang menunggu persetujuan. Silakan tunggu beberapa saat lagi.</p>
        <button onclick="window.location.reload()" class="bg-secondary text-secondary-foreground hover:bg-secondary/80 h-10 px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center gap-2 mt-2">
            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Muat Ulang Halaman
        </button>
    </div>

@else

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
@endif
    </div>
@endsection
