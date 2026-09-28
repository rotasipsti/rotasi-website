<div x-data="networkStatus()" x-init="init()" class="relative group">
    <!-- Ping Indicator Button -->
    <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-md hover:bg-accent transition-colors focus:outline-none" :title="'Ping: ' + ping + 'ms'">
        <span :class="colorClass" class="flex items-center">
            <i data-lucide="wifi" class="h-5 w-5"></i>
        </span>
        <span class="text-xs font-medium hidden sm:block" :class="colorClass" x-text="pingText"></span>
    </button>
    
    <!-- Dropdown / Speed Test Modal -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition.opacity.duration.200ms
         class="fixed left-4 right-4 top-16 sm:absolute sm:top-10 sm:left-auto sm:right-0 mt-2 sm:mt-1 sm:w-72 rounded-md shadow-lg bg-card border border-border/50 overflow-hidden z-50 p-4" 
         style="display: none;">
        

        <h3 class="text-sm font-bold mb-2">Status Jaringan Anda</h3>
        
        <div class="flex items-center justify-between mb-4 text-sm">
            <span class="text-muted-foreground">Latensi (Ping) :</span>
            <span class="font-mono font-bold" :class="colorClass" x-text="ping + ' ms'"></span>
        </div>
        
        <div class="text-xs text-muted-foreground mb-4">
            <p x-show="ping <= 60">Koneksi internet Anda sangat baik.</p>
            <p x-show="ping > 60 && ping < 120">Koneksi internet Anda cukup baik.</p>
            <p x-show="ping >= 120">Koneksi internet Anda buruk atau lambat. Gunakan koneksi internet yang lebih stabil untuk mendapatkan performa yang lebih baik.</p>
            <p x-show="ping === 0 || ping === 'RTO'">Terputus dari server. Periksa koneksi internet Anda.</p>
        </div>

        <button @click="forceCheck()" :disabled="isChecking" class="w-full flex items-center justify-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/80 px-4 py-2 rounded-md text-xs font-medium transition-colors disabled:opacity-50">
            <i data-lucide="refresh-cw" class="h-3 w-3" :class="{'animate-spin': isChecking}"></i>
            <span x-text="isChecking ? 'Menguji...' : 'Uji Ulang Ping'"></span>
        </button>
    </div>
</div>

<script>
    function networkStatus() {
        return {
            open: false,
            ping: 0,
            pingText: 'Menghitung...',
            colorClass: 'text-muted-foreground',
            isChecking: false,
            interval: null,
            
            init() {
                this.checkPing();
                // Check every 3 seconds
                this.interval = setInterval(() => {
                    if (!this.isChecking) { // Prevent overlap if currently checking
                        this.checkPing();
                    }
                }, 3000);
            },
            
            async forceCheck() {
                this.isChecking = true;
                this.pingText = 'Menguji...';
                this.colorClass = 'text-muted-foreground';
                await this.checkPing();
                this.isChecking = false;
            },

            async checkPing() {
                const startTime = performance.now();
                try {
                    // Fetch a tiny static file to avoid hitting PHP backend logic, preventing server load
                    const response = await fetch('/rotasi logo.png?cache=' + Math.random(), { 
                        method: 'HEAD',
                        cache: 'no-store'
                    });
                    
                    if (response.ok) {
                        const endTime = performance.now();
                        this.ping = Math.round(endTime - startTime);
                        
                        this.pingText = this.ping + 'ms';
                        
                        if (this.ping <= 60) {
                            this.colorClass = 'text-green-500';
                        } else if (this.ping < 120) {
                            this.colorClass = 'text-yellow-500';
                        } else {
                            this.colorClass = 'text-red-500';
                        }
                    } else {
                        this.setOffline();
                    }
                } catch (error) {
                    this.setOffline();
                }
            },
            
            setOffline() {
                this.ping = 'RTO';
                this.pingText = 'Offline';
                this.colorClass = 'text-red-500';
            }
        }
    }
</script>
