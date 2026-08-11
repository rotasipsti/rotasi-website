@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold font-bebas-neue tracking-wider">Informasi Sistem</h1>
            <p class="text-muted-foreground">Spesifikasi server dan penggunaan sumber daya aplikasi</p>
        </div>
    </div>


    <!-- Hardware & Resource Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Penyimpanan Server -->
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow flex flex-col">
            <div class="p-6 border-b border-border/50 flex items-center gap-3">
                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                    <i data-lucide="hard-drive" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Penyimpanan</h3>
                    <p class="text-sm text-muted-foreground">Kapasitas Penyimpanan (Storage)</p>
                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center items-center">
                <!-- Gauge Chart -->
                <div class="relative w-56 h-28 mx-auto mb-4">
                    <svg viewBox="0 0 100 50" class="w-full h-full drop-shadow-sm overflow-visible">
                        <!-- Background track -->
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="text-secondary" stroke-linecap="round" pathLength="100"></path>
                        <!-- Value track -->
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="{{ $diskUsagePercent > 80 ? 'text-destructive' : 'text-primary' }} transition-all duration-1000 ease-out" stroke-linecap="round" pathLength="100" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $diskUsagePercent }}"></path>
                    </svg>
                    <!-- Percentage Text -->
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center justify-end h-full pb-1">
                        <span class="text-4xl font-bold font-bebas-neue tracking-wider {{ $diskUsagePercent > 80 ? 'text-destructive' : 'text-primary' }}">{{ $diskUsagePercent }}%</span>
                        <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider leading-none">Terpakai</span>
                    </div>
                </div>

                <!-- Info Detail -->
                <div class="text-center mt-2 space-y-1">
                    <p class="text-sm font-medium text-foreground">
                        <span class="font-bold">{{ $formatBytes($usedDisk) }}</span> dari <span class="font-bold">{{ $formatBytes($totalDisk) }}</span> digunakan
                    </p>
                    <p class="text-sm text-muted-foreground">
                        sisa : <span class="font-semibold">{{ $formatBytes($freeDisk) }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Penggunaan Memori -->
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow flex flex-col">
            <div class="p-6 border-b border-border/50 flex items-center gap-3">
                <div class="p-2 rounded-lg bg-orange-500/10 text-orange-500">
                    <i data-lucide="circuit-board" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Memori</h3>
                    <p class="text-sm text-muted-foreground">Kapasitas Memori (RAM)</p>
                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center items-center">
                @php
                    $memLimitStr = ini_get('memory_limit');
                    $memLimitBytes = -1;
                    if (preg_match('/^(\d+)(.)$/i', trim($memLimitStr), $matches)) {
                        $val = (int)$matches[1];
                        $unit = strtoupper($matches[2]);
                        if ($unit == 'M') $memLimitBytes = $val * 1024 * 1024;
                        elseif ($unit == 'G') $memLimitBytes = $val * 1024 * 1024 * 1024;
                        elseif ($unit == 'K') $memLimitBytes = $val * 1024;
                    }
                    $memPercent = $memLimitBytes > 0 ? min(round(($memoryUsage / $memLimitBytes) * 100, 1), 100) : 100;
                    $isMemWarning = $memPercent > 80;
                @endphp
                <div class="relative w-56 h-28 mx-auto mb-4">
                    <svg viewBox="0 0 100 50" class="w-full h-full drop-shadow-sm overflow-visible">
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="text-secondary" stroke-linecap="round" pathLength="100"></path>
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="{{ $isMemWarning ? 'text-destructive' : 'text-orange-500' }} transition-all duration-1000 ease-out" stroke-linecap="round" pathLength="100" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $memPercent }}"></path>
                    </svg>
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center justify-end h-full pb-1">
                        <span class="text-4xl font-bold font-bebas-neue tracking-wider {{ $isMemWarning ? 'text-destructive' : 'text-orange-500' }}">
                            {{ $memLimitBytes > 0 ? $memPercent.'%' : 'N/A' }}
                        </span>
                        <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider leading-none">Terpakai</span>
                    </div>
                </div>
                <div class="text-center mt-2 space-y-1">
                    @if($memLimitBytes > 0)
                        <p class="text-sm font-medium text-foreground">
                            <span class="font-bold">{{ $formatBytes($memoryUsage) }}</span> dari <span class="font-bold">{{ $formatBytes($memLimitBytes) }}</span> digunakan
                        </p>
                        <p class="text-sm text-muted-foreground">
                            sisa : <span class="font-semibold">{{ $formatBytes(max($memLimitBytes - $memoryUsage, 0)) }}</span>
                        </p>
                    @else
                        <p class="text-sm font-medium text-foreground">
                            <span class="font-bold">{{ $formatBytes($memoryUsage) }}</span> digunakan
                        </p>
                        <p class="text-sm text-muted-foreground">
                            sisa : <span class="font-semibold">Tidak Terbatas</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Beban CPU -->
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow flex flex-col">
            <div class="p-6 border-b border-border/50 flex items-center gap-3">
                <div class="p-2 rounded-lg bg-blue-500/10 text-blue-500">
                    <i data-lucide="cpu" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">CPU Server</h3>
                    <p class="text-sm text-muted-foreground">Kapasitas CPU Server</p>
                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center items-center">
                @php
                    $cpuSupported = $cpuLoad !== null;
                    $cpuPercentVal = $cpuSupported ? min(round((float)$cpuLoad * 100, 1), 100) : 0;
                    $isCpuWarning = $cpuPercentVal > 80;
                @endphp
                <div class="relative w-56 h-28 mx-auto mb-4">
                    <svg viewBox="0 0 100 50" class="w-full h-full drop-shadow-sm overflow-visible">
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="text-secondary" stroke-linecap="round" pathLength="100"></path>
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="{{ $isCpuWarning ? 'text-destructive' : 'text-primary' }} transition-all duration-1000 ease-out" stroke-linecap="round" pathLength="100" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $cpuPercentVal }}"></path>
                    </svg>
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center justify-end h-full pb-1">
                        <span class="text-4xl font-bold font-bebas-neue tracking-wider {{ $isCpuWarning ? 'text-destructive' : ($cpuSupported ? 'text-primary' : 'text-muted-foreground') }}">
                            {{ $cpuSupported ? $cpuPercentVal.'%' : 'N/A' }}
                        </span>
                        <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider leading-none">Terpakai</span>
                    </div>
                </div>
                
                <div class="text-center mt-2 space-y-1">
                    @if($cpuSupported)
                        <p class="text-sm font-medium text-foreground">
                            <span class="font-bold">{{ $cpuPercentVal }}%</span> dari <span class="font-bold">100%</span> kapasitas
                        </p>
                        <p class="text-sm text-muted-foreground">
                            sisa : <span class="font-semibold">{{ 100 - $cpuPercentVal }}%</span>
                        </p>
                    @else
                        <p class="text-sm font-medium text-foreground">
                            <span class="font-bold">OS Tidak Didukung</span>
                        </p>
                        <p class="text-sm text-muted-foreground">
                            sisa : <span class="font-semibold">N/A</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

    </div>
    
    <!-- Software & OS Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6 flex flex-col items-center text-center justify-center space-y-3">
            <div class="p-3 rounded-full bg-primary/10 text-primary">
                <i data-lucide="server" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Sistem Operasi</p>
                <h3 class="text-lg font-bold">{{ $os }}</h3>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6 flex flex-col items-center text-center justify-center space-y-3">
            <div class="p-3 rounded-full bg-[#777BB4]/10 text-[#777BB4]">
                <i data-lucide="file-code" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Versi PHP</p>
                <h3 class="text-lg font-bold">{{ $phpVersion }}</h3>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6 flex flex-col items-center text-center justify-center space-y-3">
            <div class="p-3 rounded-full bg-[#FF2D20]/10 text-[#FF2D20]">
                <i data-lucide="boxes" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Versi Laravel</p>
                <h3 class="text-lg font-bold">{{ $laravelVersion }}</h3>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6 flex flex-col items-center text-center justify-center space-y-3">
            <div class="p-3 rounded-full bg-[#4479A1]/10 text-[#4479A1]">
                <i data-lucide="database" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Versi MySQL</p>
                <h3 class="text-lg font-bold">{{ $mysqlVersion }}</h3>
            </div>
        </div>
    </div>

    <!-- Informasi Ekstra -->
    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow mt-6">
        <div class="p-4 border-b border-border/50 flex items-center gap-2">
            <i data-lucide="info" class="h-4 w-4 text-muted-foreground"></i>
            <h3 class="font-semibold text-sm">Informasi Ekstra</h3>
        </div>
        <div class="p-4 text-sm text-muted-foreground">
            <p><strong>Server Software:</strong> {{ $serverSoftware }}</p>
            <p class="mt-2 text-xs opacity-70">
                Catatan: Data penyimpanan dan memori yang ditampilkan adalah alokasi yang dibaca oleh modul PHP saat ini. Jika Anda menggunakan shared hosting, kapasitas yang terlihat mungkin adalah kapasitas keseluruhan server fisik, bukan limit kuota akun Anda.
            </p>
        </div>
    </div>
</div>
@endsection
