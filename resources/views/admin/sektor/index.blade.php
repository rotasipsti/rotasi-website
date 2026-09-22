@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Data Sektor</h1>
            <p class="text-muted-foreground mt-1">Kelola dan pantau seluruh data sektor beserta mentor dan peserta di dalamnya.</p>
        </div>
        <div class="flex items-center gap-4 bg-card border border-border/50 rounded-xl px-4 py-3 shadow-sm shrink-0 w-full sm:w-auto">
            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <i data-lucide="layers" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Total Sektor</p>
                <p class="text-2xl font-bold leading-none">{{ $totalSectors }}</p>
            </div>
        </div>
    </div>

    @if(count($sectorsData) > 0)
        <div class="space-y-6">
            @foreach($sectorsData as $sector)
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow-sm overflow-hidden">
                    <!-- Header Sektor -->
                    <div class="bg-muted/30 border-b border-border/50 px-4 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-3 w-full">
                            <div class="h-10 w-10 rounded-lg bg-primary text-primary-foreground flex items-center justify-center font-bold text-lg shrink-0 shadow-sm">
                                {{ $sector['number'] }}
                            </div>
                            <div class="overflow-hidden">
                                <h2 class="text-lg sm:text-xl font-bold truncate">{{ $sector['name'] }}</h2>
                                <p class="text-xs sm:text-sm text-muted-foreground">{{ $sector['peserta_count'] }} Peserta</p>
                            </div>
                        </div>
                    </div>

                    <!-- Body Sektor -->
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <!-- Mentor Column -->
                            <div class="lg:col-span-1">
                                <h3 class="text-sm font-semibold flex items-center gap-2 mb-3 sm:mb-4 text-muted-foreground uppercase tracking-wider">
                                    <i data-lucide="user-check" class="h-4 w-4"></i> Mentor Sektor
                                </h3>
                                <div class="space-y-3">
                                    @forelse($sector['mentors'] as $mentor)
                                        <div class="flex items-center gap-3 p-3 rounded-lg border border-border/50 bg-background shadow-sm">
                                            <div class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold shrink-0 border border-primary/20">
                                                {{ substr($mentor->name, 0, 1) }}
                                            </div>
                                            <div class="overflow-hidden flex-1">
                                                <p class="font-semibold text-sm truncate" title="{{ $mentor->name }}">{{ $mentor->name }}</p>
                                                <p class="text-xs text-muted-foreground truncate">{{ $mentor->email }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 rounded-lg border border-dashed border-border/70 text-center bg-muted/20">
                                            <p class="text-sm text-muted-foreground">Belum ada mentor di sektor ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Peserta Column -->
                            <div class="lg:col-span-2">
                                <h3 class="text-sm font-semibold flex items-center gap-2 mb-3 sm:mb-4 text-muted-foreground uppercase tracking-wider">
                                    <i data-lucide="users" class="h-4 w-4"></i> Daftar Peserta
                                </h3>
                                @if(count($sector['peserta']) > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[350px] overflow-y-auto pr-1 sm:pr-2 custom-scrollbar">
                                        @foreach($sector['peserta'] as $peserta)
                                            <div class="flex items-center justify-between p-3 rounded-lg border border-border/50 bg-background hover:bg-muted/30 transition-colors shadow-sm gap-2">
                                                <div class="overflow-hidden flex-1">
                                                    <p class="font-medium text-sm truncate" title="{{ $peserta->name }}">{{ $peserta->name }}</p>
                                                    @if($peserta->nim)
                                                        <p class="text-xs text-muted-foreground mt-0.5 truncate">{{ $peserta->nim }}</p>
                                                    @endif
                                                </div>
                                                <div class="shrink-0 text-[10px] sm:text-xs text-muted-foreground bg-muted/50 px-2 py-1 rounded-md border border-border/50 whitespace-nowrap">
                                                    {{ $peserta->custom_id }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-6 rounded-lg border border-dashed border-border/70 flex flex-col items-center justify-center text-center bg-muted/20">
                                        <i data-lucide="users-2" class="h-8 w-8 text-muted-foreground/50 mb-2"></i>
                                        <p class="text-sm font-medium">Belum Ada Peserta</p>
                                        <p class="text-xs text-muted-foreground mt-1">Belum ada peserta yang tergabung dalam sektor ini.</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-xl border border-dashed border-border/70 bg-card p-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 mb-4">
                <i data-lucide="layers" class="h-6 w-6 text-primary"></i>
            </div>
            <h3 class="text-lg font-semibold mb-1">Belum Ada Sektor</h3>
            <p class="text-muted-foreground text-sm max-w-sm mx-auto">Data sektor belum tersedia. Silakan konfigurasi password sektor di menu Pengaturan Password.</p>
        </div>
    @endif
</div>
@endsection
