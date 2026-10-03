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
    <div x-data="systemInfo()" x-init="init()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
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
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" :class="disk.percent > 80 ? 'text-destructive' : 'text-primary'" class="transition-all duration-1000 ease-out" stroke-linecap="round" pathLength="100" stroke-dasharray="100" :stroke-dashoffset="100 - disk.percent"></path>
                    </svg>
                    <!-- Percentage Text -->
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center justify-end h-full pb-1">
                        <span class="text-4xl font-bold font-bebas-neue tracking-wider" :class="disk.percent > 80 ? 'text-destructive' : 'text-primary'" x-text="disk.percent + '%'"></span>
                        <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider leading-none">Terpakai</span>
                    </div>
                </div>

                <!-- Info Detail -->
                <div class="text-center mt-2 space-y-1">
                    <p class="text-sm font-medium text-foreground">
                        <span class="font-bold" x-text="disk.used"></span> dari <span class="font-bold" x-text="disk.total"></span> digunakan
                    </p>
                    <p class="text-sm text-muted-foreground">
                        sisa : <span class="font-semibold" x-text="disk.free"></span>
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
                <div class="relative w-56 h-28 mx-auto mb-4">
                    <svg viewBox="0 0 100 50" class="w-full h-full drop-shadow-sm overflow-visible">
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="text-secondary" stroke-linecap="round" pathLength="100"></path>
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" :class="mem.percent > 80 ? 'text-destructive' : 'text-orange-500'" class="transition-all duration-1000 ease-out" stroke-linecap="round" pathLength="100" stroke-dasharray="100" :stroke-dashoffset="100 - mem.percent"></path>
                    </svg>
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center justify-end h-full pb-1">
                        <span class="text-4xl font-bold font-bebas-neue tracking-wider" :class="mem.percent > 80 ? 'text-destructive' : 'text-orange-500'" x-text="mem.hasLimit ? mem.percent + '%' : 'N/A'"></span>
                        <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider leading-none">Terpakai</span>
                    </div>
                </div>
                <div class="text-center mt-2 space-y-1">
                    <template x-if="mem.hasLimit">
                        <div>
                            <p class="text-sm font-medium text-foreground">
                                <span class="font-bold" x-text="mem.used"></span> dari <span class="font-bold" x-text="mem.total"></span> digunakan
                            </p>
                            <p class="text-sm text-muted-foreground">
                                sisa : <span class="font-semibold" x-text="mem.free"></span>
                            </p>
                        </div>
                    </template>
                    <template x-if="!mem.hasLimit">
                        <div>
                            <p class="text-sm font-medium text-foreground">
                                <span class="font-bold" x-text="mem.used"></span> digunakan
                            </p>
                            <p class="text-sm text-muted-foreground">
                                sisa : <span class="font-semibold">Tidak Terbatas</span>
                            </p>
                        </div>
                    </template>
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
                <div class="relative w-56 h-28 mx-auto mb-4">
                    <svg viewBox="0 0 100 50" class="w-full h-full drop-shadow-sm overflow-visible">
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" class="text-secondary" stroke-linecap="round" pathLength="100"></path>
                        <path d="M 10 45 A 40 40 0 0 1 90 45" fill="none" stroke="currentColor" stroke-width="10" :class="cpu.percent > 80 ? 'text-destructive' : 'text-primary'" class="transition-all duration-1000 ease-out" stroke-linecap="round" pathLength="100" stroke-dasharray="100" :stroke-dashoffset="100 - cpu.percent"></path>
                    </svg>
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center justify-end h-full pb-1">
                        <span class="text-4xl font-bold font-bebas-neue tracking-wider" :class="cpu.percent > 80 ? 'text-destructive' : (cpu.supported ? 'text-primary' : 'text-muted-foreground')" x-text="cpu.supported ? cpu.percent + '%' : 'N/A'"></span>
                        <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider leading-none">Terpakai</span>
                    </div>
                </div>
                
                <div class="text-center mt-2 space-y-1">
                    <template x-if="cpu.supported">
                        <div>
                            <p class="text-sm font-medium text-foreground">
                                <span class="font-bold" x-text="cpu.percent + '%'"></span> dari <span class="font-bold">100%</span> kapasitas
                            </p>
                            <p class="text-sm text-muted-foreground">
                                sisa : <span class="font-semibold" x-text="(100 - cpu.percent).toFixed(1) + '%'"></span>
                            </p>
                        </div>
                    </template>
                    <template x-if="!cpu.supported">
                        <div>
                            <p class="text-sm font-medium text-foreground">
                                <span class="font-bold">OS Tidak Didukung</span>
                            </p>
                            <p class="text-sm text-muted-foreground">
                                sisa : <span class="font-semibold">N/A</span>
                            </p>
                        </div>
                    </template>
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

<script>
    function systemInfo() {
        return {
            disk: {
                percent: {{ $diskUsagePercent }},
                used: '{{ $formatBytes($usedDisk) }}',
                total: '{{ $formatBytes($totalDisk) }}',
                free: '{{ $formatBytes($freeDisk) }}'
            },
            mem: {
                percent: {{ isset($memPercent) ? $memPercent : 0 }},
                used: '{{ $formatBytes($memoryUsage) }}',
                total: '{{ isset($memLimitBytes) && $memLimitBytes > 0 ? $formatBytes($memLimitBytes) : "Tidak Terbatas" }}',
                free: '{{ isset($memLimitBytes) && $memLimitBytes > 0 ? $formatBytes(max($memLimitBytes - $memoryUsage, 0)) : "Tidak Terbatas" }}',
                hasLimit: {{ isset($memLimitBytes) && $memLimitBytes > 0 ? 'true' : 'false' }}
            },
            cpu: {
                percent: {{ isset($cpuPercentVal) ? $cpuPercentVal : 0 }},
                supported: {{ isset($cpuSupported) && $cpuSupported ? 'true' : 'false' }}
            },
            
            init() {
                // Determine initial values for variables missing from blade because PHP was removed
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
                    
                    $cpuSupported = $cpuLoad !== null;
                    $cpuPercentVal = $cpuSupported ? min(round((float)$cpuLoad * 100, 1), 100) : 0;
                @endphp
                
                // Set initial data
                this.mem.percent = {{ $memPercent }};
                this.mem.total = '{{ $memLimitBytes > 0 ? $formatBytes($memLimitBytes) : "Tidak Terbatas" }}';
                this.mem.free = '{{ $memLimitBytes > 0 ? $formatBytes(max($memLimitBytes - $memoryUsage, 0)) : "Tidak Terbatas" }}';
                this.mem.hasLimit = {{ $memLimitBytes > 0 ? 'true' : 'false' }};
                
                this.cpu.percent = {{ $cpuPercentVal }};
                this.cpu.supported = {{ $cpuSupported ? 'true' : 'false' }};

                // Polling every 3 seconds
                setInterval(() => {
                    this.fetchStats();
                }, 3000);
            },
            
            async fetchStats() {
                try {
                    const response = await fetch('{{ route("admin.system-info.stats") }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (response.ok) {
                        const data = await response.json();
                        this.disk = data.disk;
                        this.mem = data.mem;
                        this.cpu = data.cpu;
                    }
                } catch (error) {
                    // silently fail on network error, will try again next interval
                }
            }
        }
    }
</script>
@endsection
