<div x-data="{
    deferredPrompt: null,
    showBanner: false,
    init() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            // Cek apakah banner boleh ditampilkan berdasarkan waktu dismiss
            if (this.shouldShowBanner()) {
                this.showBanner = true;
            }
        });
        window.addEventListener('appinstalled', (evt) => {
            this.showBanner = false;
            this.deferredPrompt = null;
        });
    },
    shouldShowBanner() {
        const dismissDate = localStorage.getItem('pwa_banner_dismissed_date');
        if (!dismissDate) return true; // Belum pernah di-dismiss
        
        const now = new Date().getTime();
        const threeDaysInMs = 3 * 24 * 60 * 60 * 1000; // 3 Hari dalam hitungan milidetik
        
        // Tampilkan lagi JIKA waktu sekarang sudah melewati 3 hari sejak di-dismiss
        return (now - parseInt(dismissDate)) > threeDaysInMs;
    },
    async installApp() {
        if (!this.deferredPrompt) return;
        this.deferredPrompt.prompt();
        const { outcome } = await this.deferredPrompt.userChoice;
        this.deferredPrompt = null;
        this.showBanner = false;
    },
    dismiss() {
        this.showBanner = false;
        // Simpan waktu saat ini ke localStorage
        localStorage.setItem('pwa_banner_dismissed_date', new Date().getTime().toString());
    }
}">
    <!-- Floating Onboarding / Reminder Banner -->
    <div x-show="showBanner" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-10"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-10"
         class="fixed bottom-0 left-0 right-0 md:bottom-6 md:left-auto md:right-6 md:w-[400px] z-[100] p-4 md:p-0 text-left"
    >
        <div class="bg-card border border-border/50 rounded-2xl shadow-2xl p-5 relative overflow-hidden backdrop-blur-sm bg-card/95">
            <!-- Dekorasi background -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-primary/10 rounded-full blur-2xl"></div>
            
            <button @click="dismiss()" class="absolute top-3 right-3 text-muted-foreground hover:text-foreground transition-colors p-1 rounded-full hover:bg-secondary">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 border border-border bg-background shadow-sm">
                    <!-- Gunakan Logo Aplikasi -->
                    <img src="{{ asset('rotasi logo.png') }}" class="w-10 h-10 object-contain" alt="ROTASI Logo">
                </div>
                <div class="pr-4">
                    <h3 class="font-bold text-foreground text-base">Install ROTASI Digital</h3>
                    <p class="text-sm text-muted-foreground mt-1 leading-relaxed">
                        Install ROTASI Digital di perangkat Anda untuk akses ROTASI yang lebih cepat.
                    </p>
                    <div class="mt-4 flex gap-2">
                        <button @click="installApp()" class="bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex-1 shadow-sm">
                            Install Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
