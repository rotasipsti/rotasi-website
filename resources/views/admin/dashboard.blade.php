@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Dashboard Admin</h1>
            <p class="text-muted-foreground">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span> 👋</p>
        </div>
    </div>



    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Total Seluruh Akun</h3>
                <i data-lucide="users" class="h-4 w-4 text-primary"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_semua_akun ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Akun terdaftar</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Total Peserta</h3>
                <i data-lucide="users" class="h-4 w-4 text-blue-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_peserta ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Peserta terdaftar</p>
            </div>
        </div>
        
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Total Mentor</h3>
                <i data-lucide="graduation-cap" class="h-4 w-4 text-green-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_mentor ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Akun terdaftar</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Divisi Acara</h3>
                <i data-lucide="calendar" class="h-4 w-4 text-orange-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_acara ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Akun terdaftar</p>
            </div>
        </div>
    </div>

    <!-- Statistik Tambahan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Sektor Aktif</h3>
                <i data-lucide="layers" class="h-4 w-4 text-purple-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">10</div>
                <p class="text-xs text-muted-foreground">Grup sektor</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Divisi Keamanan</h3>
                <i data-lucide="shield" class="h-4 w-4 text-emerald-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_keamanan ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Akun terdaftar</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Panitia</h3>
                <i data-lucide="briefcase" class="h-4 w-4 text-pink-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_panitia ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Akun terdaftar</p>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                <h3 class="text-sm font-medium">Total Tugas</h3>
                <i data-lucide="file-text" class="h-4 w-4 text-indigo-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $total_tugas ?? 0 }}</div>
                <p class="text-xs text-muted-foreground">Tugas yang dibuat</p>
            </div>
        </div>
    </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="mt-8 mb-8">
        <h2 class="text-xl font-bold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="h-5 w-5 text-blue-600"></i>
                </div>
                <span class="text-sm font-medium">Data Akun</span>
            </a>
            
            <a href="{{ route('admin.approvals.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="check-circle" class="h-5 w-5 text-yellow-600"></i>
                </div>
                <span class="text-sm font-medium">Persetujuan Akun</span>
            </a>

            <a href="{{ route('admin.cms.downloads.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="download" class="h-5 w-5 text-purple-600"></i>
                </div>
                <span class="text-sm font-medium">Manajemen Unduhan</span>
            </a>
            
            <a href="{{ route('admin.cms.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-border/50 bg-card hover:bg-accent hover:text-accent-foreground transition-colors shadow-sm text-center group">
                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="layout" class="h-5 w-5 text-green-600"></i>
                </div>
                <span class="text-sm font-medium">Konten Publik</span>
            </a>
        </div>
    </div>
</div>
@endsection
