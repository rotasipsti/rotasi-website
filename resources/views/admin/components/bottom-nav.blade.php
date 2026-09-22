<div x-data="{ 
        open: false,
        startY: 0,
        currentY: 0,
        touchStart(e) {
            this.startY = e.touches[0].clientY;
            this.currentY = 0;
        },
        touchMove(e) {
            this.currentY = e.touches[0].clientY;
        },
        touchEnd(e) {
            if (this.currentY === 0) return;
            const target = e.target.closest('.custom-scrollbar');
            if (this.open && target && target.scrollTop > 0) return;
            
            let diff = this.startY - this.currentY;
            if (!this.open && diff > 30) {
                this.open = true;
            } else if (this.open && diff < -30) {
                this.open = false;
            }
        }
    }" 
    class="md:hidden">
    
    <!-- Overlay -->
    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40" style="display: none;"></div>

    @php
        $role = auth()->user()->role;
        $profileRouteName = 'peserta.profile.edit';
        if ($role === 'admin') $profileRouteName = 'admin.profile.edit';
        elseif ($role === 'mentor') $profileRouteName = 'mentor.profile.edit';
        elseif ($role === 'acara') $profileRouteName = 'acara.profile.edit';
        elseif ($role === 'keamanan') $profileRouteName = 'keamanan.profile.edit';
        elseif ($role === 'panitia') $profileRouteName = 'panitia.profile.edit';
    @endphp

    <!-- Expandable Drawer -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-card rounded-t-[2rem] border-t border-border/50 shadow-[0_-10px_40px_rgba(0,0,0,0.2)] flex flex-col transition-all duration-300 ease-in-out"
         :style="open ? 'height: 75vh; padding-bottom: env(safe-area-inset-bottom);' : 'height: calc(120px + env(safe-area-inset-bottom));'"
         :class="!open ? 'touch-none' : ''"
         @touchstart="touchStart" @touchmove="touchMove" @touchend="touchEnd">
        
        <!-- Drag Handle / Toggle -->
        <div class="w-full flex justify-center pt-2 pb-1 cursor-pointer flex-shrink-0 touch-none" @click="open = !open">
            <div class="flex flex-col items-center justify-center text-muted-foreground/70 hover:text-foreground transition-colors">
                <i data-lucide="chevron-up" class="h-4 w-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                <span class="text-[9px] font-medium tracking-wide uppercase mt-0.5" x-text="open ? 'Tutup Menu' : 'Menu Lainnya'"></span>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="px-2 pt-1 pb-8 flex-1 w-full transition-all overscroll-contain" 
             :class="open ? 'overflow-y-auto custom-scrollbar' : 'overflow-hidden'"
             style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem 0.25rem; align-content: start;">
             
             <!-- 1. Dashboard (Always First Row) -->
             <a wire:navigate href="{{ route('dashboard') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('dashboard*') || request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                 <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('dashboard*') || request()->routeIs('admin.dashboard') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                     <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                 </div>
                 <span class="text-[10px] font-medium leading-tight">Dashboard</span>
             </a>

             @if(auth()->user()->role === 'admin')
                 <!-- Admin First Row -->
                 <a wire:navigate href="{{ route('admin.cms.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.cms.*') && !request()->routeIs('admin.cms.downloads.*') && !request()->routeIs('admin.cms.banners.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.cms.*') && !request()->routeIs('admin.cms.downloads.*') && !request()->routeIs('admin.cms.banners.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="globe" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">CMS</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.users.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="users" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Akun</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.sektor.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.sektor.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.sektor.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="layers" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Sektor</span>
                 </a>
                 <a wire:navigate href="{{ route($profileRouteName) }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs($profileRouteName) ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Profil</span>
                 </a>
                 
                 <!-- Admin Second Row & Beyond -->
                 <a wire:navigate href="{{ route('admin.cms.downloads.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.cms.downloads.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.cms.downloads.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Unduhan</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.cms.banners.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.cms.banners.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.cms.banners.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="image" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Banner</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.passwords.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.passwords.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.passwords.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="key" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Password</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.approvals.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.approvals.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.approvals.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user-check" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Persetujuan</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.tasks') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.tasks') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.tasks') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="file-text" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Tugas</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.submissions') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.submissions') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download-cloud" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Pengumpulan</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.exit.history') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.exit.history') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="history" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Izin</span>
                 </a>
                 <a wire:navigate href="{{ route('admin.system-info.index') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('admin.system-info.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('admin.system-info.*') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="server" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Server</span>
                 </a>

             @elseif(auth()->user()->role === 'mentor')
                 <a wire:navigate href="{{ route('mentor.peserta') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('mentor.peserta') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('mentor.peserta') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="users" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Peserta</span>
                 </a>
                 <a wire:navigate href="{{ route('mentor.approvals') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('mentor.approvals') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('mentor.approvals') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user-check" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Persetujuan</span>
                 </a>
                 <a wire:navigate href="{{ route($profileRouteName) }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs($profileRouteName) ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Profil</span>
                 </a>
                 <a wire:navigate href="{{ route('mentor.submissions') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('mentor.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('mentor.submissions') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="check-square" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Submission</span>
                 </a>
                 <a wire:navigate href="{{ route('mentor.tasks') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('mentor.tasks') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('mentor.tasks') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="file-text" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Tugas</span>
                 </a>
                 <a wire:navigate href="{{ route('mentor.exit.history') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('mentor.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('mentor.exit.history') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="history" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Izin</span>
                 </a>
                 <a wire:navigate href="{{ route('shared.downloads') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('shared.downloads') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Dokumen</span>
                 </a>

             @elseif(auth()->user()->role === 'acara')
                 <a wire:navigate href="{{ route('acara.tasks') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('acara.tasks') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('acara.tasks') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="file-text" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Tugas</span>
                 </a>
                 <a wire:navigate href="{{ route('acara.submissions') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('acara.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('acara.submissions') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download-cloud" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Pengumpulan</span>
                 </a>
                 <a wire:navigate href="{{ route($profileRouteName) }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs($profileRouteName) ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Profil</span>
                 </a>
                 <a wire:navigate href="{{ route('acara.exit.history') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('acara.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('acara.exit.history') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="history" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Izin</span>
                 </a>
                 <a wire:navigate href="{{ route('shared.downloads') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('shared.downloads') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Dokumen</span>
                 </a>

             @elseif(auth()->user()->role === 'peserta')
                 <a wire:navigate href="{{ route('peserta.tasks') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('peserta.tasks') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('peserta.tasks') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="book-open" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Tugas</span>
                 </a>
                 <a wire:navigate href="{{ route('peserta.submissions') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('peserta.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('peserta.submissions') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="upload" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Pengumpulan</span>
                 </a>
                 <a wire:navigate href="{{ route($profileRouteName) }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs($profileRouteName) ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Profil</span>
                 </a>
                 <a wire:navigate href="{{ route('shared.downloads') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('shared.downloads') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Dokumen</span>
                 </a>

             @elseif(auth()->user()->role === 'keamanan')
                 <a wire:navigate href="{{ route('keamanan.exit.scanner') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('keamanan.exit.scanner') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('keamanan.exit.scanner') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="scan" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Scanner</span>
                 </a>
                 <a wire:navigate href="{{ route('keamanan.exit.history') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('keamanan.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('keamanan.exit.history') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="history" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Izin</span>
                 </a>
                 <a wire:navigate href="{{ route($profileRouteName) }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs($profileRouteName) ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Profil</span>
                 </a>
                 <a wire:navigate href="{{ route('shared.downloads') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('shared.downloads') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Dokumen</span>
                 </a>

             @elseif(auth()->user()->role === 'panitia')
                 <a wire:navigate href="{{ route('panitia.exit.history') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('panitia.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('panitia.exit.history') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="history" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Izin</span>
                 </a>
                 <a wire:navigate href="{{ route('shared.downloads') }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs('shared.downloads') ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="download" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Dokumen</span>
                 </a>
                 <a wire:navigate href="{{ route($profileRouteName) }}" class="flex flex-col items-center justify-start text-center {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
                     <div class="h-11 w-11 rounded-[14px] flex items-center justify-center mb-1.5 {{ request()->routeIs($profileRouteName) ? 'bg-primary/10 shadow-sm' : 'bg-muted/50' }}">
                         <i data-lucide="user" class="h-5 w-5"></i>
                     </div>
                     <span class="text-[10px] font-medium leading-tight">Profil</span>
                 </a>
             @endif

        </div>
    </div>
</div>
