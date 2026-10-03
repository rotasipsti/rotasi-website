<div x-data="{
    deferredPrompt: null,
    showInstallBtn: false,
    init() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallBtn = true;
        });
        window.addEventListener('appinstalled', (evt) => {
            this.showInstallBtn = false;
            this.deferredPrompt = null;
        });
    },
    async installApp() {
        if (!this.deferredPrompt) return;
        this.deferredPrompt.prompt();
        const { outcome } = await this.deferredPrompt.userChoice;
        this.deferredPrompt = null;
        this.showInstallBtn = false;
    }
}" class="w-full sm:w-auto">
    <button 
        x-show="showInstallBtn" 
        style="display: none;"
        @click="installApp()" 
        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm"
    >
        <i data-lucide="download" class="w-4 h-4"></i>
        Install Aplikasi
    </button>
</div>
