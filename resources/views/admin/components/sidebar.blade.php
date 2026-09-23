<aside class="hidden md:block fixed top-16 bottom-0 left-0 z-40 w-64 bg-card border-r border-border/50 transform transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <div class="py-4 overflow-y-auto h-full">
        <nav class="space-y-1 px-3">
            
            <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard*') || request()->routeIs('admin.dashboard') || request()->routeIs('stakeholder.dashboard') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                <i data-lucide="layout-dashboard" class="mr-3 h-5 w-5"></i>
                Dashboard
            </a>

            @if(auth()->user()->role === 'admin')
                <a wire:navigate href="{{ route('admin.cms.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.cms.*') && !request()->routeIs('admin.cms.downloads.*') && !request()->routeIs('admin.cms.banners.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="globe" class="mr-3 h-5 w-5"></i>
                    Konten Publik (CMS)
                </a>

                <a wire:navigate href="{{ route('admin.cms.downloads.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.cms.downloads.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Manajemen Unduhan
                </a>

                <a wire:navigate href="{{ route('admin.cms.banners.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.cms.banners.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="image" class="mr-3 h-5 w-5"></i>
                    Manajemen Banner
                </a>

                <a wire:navigate href="{{ route('admin.passwords.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.passwords.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="key" class="mr-3 h-5 w-5"></i>
                    Password Manajemen
                </a>
                
                <a wire:navigate href="{{ route('admin.approvals.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.approvals.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="user-check" class="mr-3 h-5 w-5"></i>
                    Persetujuan Akun
                </a>
                
                <a wire:navigate href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="users" class="mr-3 h-5 w-5"></i>
                    Data Akun
                </a>
                
                <a wire:navigate href="{{ route('admin.sektor.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.sektor.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="layers" class="mr-3 h-5 w-5"></i>
                    Data Sektor
                </a>
                
                <a wire:navigate href="{{ route('admin.tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="file-text" class="mr-3 h-5 w-5"></i>
                    Manajemen Tugas
                </a>
                
                <a wire:navigate href="{{ route('admin.submissions') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download-cloud" class="mr-3 h-5 w-5"></i>
                    Data Pengumpulan
                </a>
                
                <a wire:navigate href="{{ route('admin.exit.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="history" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin
                </a>
                
                <a wire:navigate href="{{ route('admin.system-info.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system-info.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="server" class="mr-3 h-5 w-5"></i>
                    Informasi Sistem
                </a>
            @elseif(auth()->user()->role === 'mentor')
                <a wire:navigate href="{{ route('mentor.peserta') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('mentor.peserta') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="users" class="mr-3 h-5 w-5"></i>
                    Peserta
                </a>
                <a wire:navigate href="{{ route('mentor.approvals') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('mentor.approvals') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="user-check" class="mr-3 h-5 w-5"></i>
                    Persetujuan Akun
                </a>
                <a wire:navigate href="{{ route('mentor.submissions') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('mentor.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="check-square" class="mr-3 h-5 w-5"></i>
                    Submission Sektor
                </a>
                <a wire:navigate href="{{ route('mentor.tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('mentor.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="file-text" class="mr-3 h-5 w-5"></i>
                    Tugas Sektor
                </a>
                <a wire:navigate href="{{ route('mentor.exit.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('mentor.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="history" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Dokumen & Berkas
                </a>
            @elseif(auth()->user()->role === 'acara')
                <a wire:navigate href="{{ route('acara.tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('acara.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="file-text" class="mr-3 h-5 w-5"></i>
                    Manajemen Tugas
                </a>
                <a wire:navigate href="{{ route('acara.submissions') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('acara.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download-cloud" class="mr-3 h-5 w-5"></i>
                    Data Pengumpulan
                </a>
                <a wire:navigate href="{{ route('acara.exit.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('acara.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="history" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Dokumen & Berkas
                </a>
            @elseif(auth()->user()->role === 'peserta')
                <a wire:navigate href="{{ route('peserta.tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('peserta.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="book-open" class="mr-3 h-5 w-5"></i>
                    Tugas Saya
                </a>
                <a wire:navigate href="{{ route('peserta.submissions') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('peserta.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="upload" class="mr-3 h-5 w-5"></i>
                    Riwayat Pengumpulan
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Dokumen & Berkas
                </a>
            @elseif(auth()->user()->role === 'keamanan')
                <a wire:navigate href="{{ route('keamanan.exit.scanner') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('keamanan.exit.scanner') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="scan" class="mr-3 h-5 w-5"></i>
                    Scanner QR Code
                </a>
                <a wire:navigate href="{{ route('keamanan.exit.my-history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('keamanan.exit.my-history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="user-check" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin Individu
                </a>
                <a wire:navigate href="{{ route('keamanan.exit.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('keamanan.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="history" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin Panitia
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Dokumen & Berkas
                </a>
            @elseif(auth()->user()->role === 'panitia')
                <a wire:navigate href="{{ route('panitia.exit.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('panitia.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="history" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Dokumen & Berkas
                </a>
            @elseif(auth()->user()->role === 'stakeholder')
                <a wire:navigate href="{{ route('stakeholder.submissions') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('stakeholder.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download-cloud" class="mr-3 h-5 w-5"></i>
                    Data Pengumpulan
                </a>
                <a wire:navigate href="{{ route('stakeholder.tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('stakeholder.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="file-text" class="mr-3 h-5 w-5"></i>
                    Tugas Acara
                </a>
                <a wire:navigate href="{{ route('stakeholder.exit.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('stakeholder.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="user-check" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin Individu
                </a>
                <a wire:navigate href="{{ route('stakeholder.exit.panitia') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('stakeholder.exit.panitia') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="history" class="mr-3 h-5 w-5"></i>
                    Riwayat Izin Panitia
                </a>
                <a wire:navigate href="{{ route('stakeholder.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('stakeholder.users.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="users" class="mr-3 h-5 w-5"></i>
                    Data Akun
                </a>
                <a wire:navigate href="{{ route('stakeholder.sektor.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('stakeholder.sektor.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="layers" class="mr-3 h-5 w-5"></i>
                    Data Sektor
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                    <i data-lucide="download" class="mr-3 h-5 w-5"></i>
                    Dokumen & Berkas
                </a>
            @endif

            <div class="my-2 border-t border-border/50"></div>
            
            @php
                $role = auth()->user()->role;
                $profileRouteName = 'peserta.profile.edit';
                
                if ($role === 'admin') {
                    $profileRouteName = 'admin.profile.edit';
                } elseif ($role === 'mentor') {
                    $profileRouteName = 'mentor.profile.edit';
                } elseif ($role === 'acara') {
                    $profileRouteName = 'acara.profile.edit';
                } elseif ($role === 'keamanan') {
                    $profileRouteName = 'keamanan.profile.edit';
                } elseif ($role === 'panitia') {
                    $profileRouteName = 'panitia.profile.edit';
                } elseif ($role === 'stakeholder') {
                    $profileRouteName = 'stakeholder.profile.edit';
                }
            @endphp
            <a wire:navigate href="{{ route($profileRouteName) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs($profileRouteName) ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}">
                <i data-lucide="user" class="mr-3 h-5 w-5"></i>
                Profil Akun
            </a>
        </nav>
    </div>
</aside>

<!-- Mobile overlay -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-30 bg-background/80 backdrop-blur-sm md:hidden" 
     style="display: none;"></div>
