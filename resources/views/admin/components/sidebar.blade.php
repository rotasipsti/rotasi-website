<aside id="admin-sidebar" 
       @mouseenter="if (!sidebarOpen) { isReady = true; sidebarHovered = true; }"
       @mouseleave="if (!sidebarOpen) { sidebarHovered = false; }"
       class="hidden md:block fixed top-16 bottom-0 left-0 z-40 bg-card border-r border-border/50 overflow-x-hidden"
       :class="{
           'transition-[width,box-shadow] duration-300 ease-in-out': isReady,
           'sidebar-expanded w-64': isExpanded,
           'w-16': !isExpanded,
           'shadow-2xl ring-1 ring-border/50': sidebarHovered && !sidebarOpen
       }">
    
    <div class="py-4 overflow-y-auto overflow-x-hidden h-full custom-scrollbar">
        <nav class="sidebar-nav space-y-1 px-3">
            
            <a wire:navigate href="{{ route('dashboard') }}" 
               :title="!isExpanded ? 'Beranda' : ''"
               class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard*') || request()->routeIs('admin.dashboard') || request()->routeIs('stakeholder.dashboard') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
               :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                <i data-lucide="layout-dashboard" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Beranda</span>
            </a>

            @if(auth()->user()->role === 'admin')
                <a wire:navigate href="{{ route('admin.cms.index') }}" 
                   :title="!isExpanded ? 'Konten Publik (CMS)' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.cms.*') && !request()->routeIs('admin.cms.downloads.*') && !request()->routeIs('admin.cms.banners.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="globe" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Konten Publik (CMS)</span>
                </a>

                <a wire:navigate href="{{ route('admin.cms.downloads.index') }}" 
                   :title="!isExpanded ? 'Manajemen Link/File' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.cms.downloads.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Link/File</span>
                </a>

                <a wire:navigate href="{{ route('admin.cms.banners.index') }}" 
                   :title="!isExpanded ? 'Manajemen Banner' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.cms.banners.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="image" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Banner</span>
                </a>

                <a wire:navigate href="{{ route('admin.announcements.index') }}" 
                   :title="!isExpanded ? 'Manajemen Pengumuman' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.announcements.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="bell" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Pengumuman</span>
                </a>

                <a wire:navigate href="{{ route('admin.passwords.index') }}" 
                   :title="!isExpanded ? 'Manajemen Sektor' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.passwords.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="boxes" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Sektor</span>
                </a>

                <a wire:navigate href="{{ route('admin.users.index') }}" 
                   :title="!isExpanded ? 'Manajemen Akun' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="users" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Akun</span>
                </a>

                <a wire:navigate href="{{ route('admin.tasks') }}" 
                   :title="!isExpanded ? 'Manajemen Tugas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="file-text" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Tugas</span>
                </a>
                
                <a wire:navigate href="{{ route('admin.approvals.index') }}" 
                   :title="!isExpanded ? 'Persetujuan Akun' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.approvals.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="user-check" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Persetujuan Akun</span>
                </a>

                <a wire:navigate href="{{ route('admin.sektor.index') }}" 
                   :title="!isExpanded ? 'Data Sektor' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.sektor.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="layers" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Data Sektor</span>
                </a>
                
                <a wire:navigate href="{{ route('admin.submissions') }}" 
                   :title="!isExpanded ? 'Data Pengumpulan' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download-cloud" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Data Pengumpulan</span>
                </a>
                
                <a wire:navigate href="{{ route('admin.exit.history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="history" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin</span>
                </a>
                
                <a wire:navigate href="{{ route('admin.system-info.index') }}" 
                   :title="!isExpanded ? 'Informasi Sistem' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.system-info.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="server" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Informasi Sistem</span>
                </a>
            @elseif(auth()->user()->role === 'mentor')
                <a wire:navigate href="{{ route('mentor.peserta') }}" 
                   :title="!isExpanded ? 'Peserta' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('mentor.peserta') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="users" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Peserta</span>
                </a>
                <a wire:navigate href="{{ route('mentor.approvals') }}" 
                   :title="!isExpanded ? 'Persetujuan Akun' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('mentor.approvals') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="user-check" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Persetujuan Akun</span>
                </a>
                <a wire:navigate href="{{ route('mentor.submissions') }}" 
                   :title="!isExpanded ? 'Submission Sektor' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('mentor.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="check-square" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Submission Sektor</span>
                </a>
                <a wire:navigate href="{{ route('mentor.tasks') }}" 
                   :title="!isExpanded ? 'Tugas Sektor' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('mentor.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="file-text" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Tugas Sektor</span>
                </a>
                <a wire:navigate href="{{ route('mentor.exit.history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('mentor.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="history" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin</span>
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" 
                   :title="!isExpanded ? 'Dokumen & Berkas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Dokumen & Berkas</span>
                </a>
            @elseif(auth()->user()->role === 'acara')
                <a wire:navigate href="{{ route('acara.tasks') }}" 
                   :title="!isExpanded ? 'Manajemen Tugas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('acara.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="file-text" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Tugas</span>
                </a>
                <a wire:navigate href="{{ route('acara.submissions') }}" 
                   :title="!isExpanded ? 'Data Pengumpulan' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('acara.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download-cloud" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Data Pengumpulan</span>
                </a>
                <a wire:navigate href="{{ route('acara.exit.history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('acara.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="history" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin</span>
                </a>
                <a wire:navigate href="{{ route('acara.downloads') }}" 
                   :title="!isExpanded ? 'Manajemen Link/File' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('acara.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="link" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Manajemen Link/File</span>
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" 
                   :title="!isExpanded ? 'Dokumen & Berkas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Dokumen & Berkas</span>
                </a>
            @elseif(auth()->user()->role === 'peserta')
                <a wire:navigate href="{{ route('peserta.tasks') }}" 
                   :title="!isExpanded ? 'Tugas Saya' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('peserta.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="book-open" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Tugas Saya</span>
                </a>
                <a wire:navigate href="{{ route('peserta.submissions') }}" 
                   :title="!isExpanded ? 'Riwayat Pengumpulan' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('peserta.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="upload" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Pengumpulan</span>
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" 
                   :title="!isExpanded ? 'Dokumen & Berkas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Dokumen & Berkas</span>
                </a>
            @elseif(auth()->user()->role === 'keamanan')
                <a wire:navigate href="{{ route('keamanan.exit.scanner') }}" 
                   :title="!isExpanded ? 'Scanner QR Code' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('keamanan.exit.scanner') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="scan" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Scanner QR Code</span>
                </a>
                <a wire:navigate href="{{ route('keamanan.exit.my-history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin Individu' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('keamanan.exit.my-history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="user-check" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin Individu</span>
                </a>
                <a wire:navigate href="{{ route('keamanan.exit.history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin Panitia' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('keamanan.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="history" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin Panitia</span>
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" 
                   :title="!isExpanded ? 'Dokumen & Berkas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Dokumen & Berkas</span>
                </a>
            @elseif(auth()->user()->role === 'panitia')
                <a wire:navigate href="{{ route('panitia.exit.history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('panitia.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="history" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin</span>
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" 
                   :title="!isExpanded ? 'Dokumen & Berkas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Dokumen & Berkas</span>
                </a>
            @elseif(auth()->user()->role === 'stakeholder')
                <a wire:navigate href="{{ route('stakeholder.submissions') }}" 
                   :title="!isExpanded ? 'Data Pengumpulan' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stakeholder.submissions') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download-cloud" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Data Pengumpulan</span>
                </a>
                <a wire:navigate href="{{ route('stakeholder.tasks') }}" 
                   :title="!isExpanded ? 'Tugas Acara' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stakeholder.tasks') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="file-text" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Tugas Acara</span>
                </a>
                <a wire:navigate href="{{ route('stakeholder.exit.history') }}" 
                   :title="!isExpanded ? 'Riwayat Izin Individu' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stakeholder.exit.history') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="user-check" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin Individu</span>
                </a>
                <a wire:navigate href="{{ route('stakeholder.exit.panitia') }}" 
                   :title="!isExpanded ? 'Riwayat Izin Panitia' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stakeholder.exit.panitia') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="history" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Riwayat Izin Panitia</span>
                </a>
                <a wire:navigate href="{{ route('stakeholder.users.index') }}" 
                   :title="!isExpanded ? 'Data Akun' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stakeholder.users.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="users" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Data Akun</span>
                </a>
                <a wire:navigate href="{{ route('stakeholder.sektor.index') }}" 
                   :title="!isExpanded ? 'Data Sektor' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stakeholder.sektor.*') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="layers" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Data Sektor</span>
                </a>
                <a wire:navigate href="{{ route('shared.downloads') }}" 
                   :title="!isExpanded ? 'Dokumen & Berkas' : ''"
                   class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shared.downloads') ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
                   :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                    <i data-lucide="download" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                    <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Dokumen & Berkas</span>
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
            <a wire:navigate href="{{ route($profileRouteName) }}" 
               :title="!isExpanded ? 'Profil Akun' : ''"
               class="sidebar-link flex items-center h-10 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($profileRouteName) ? 'bg-primary text-primary-foreground' : 'text-foreground hover:bg-accent hover:text-accent-foreground' }}"
               :class="isExpanded ? 'px-3 justify-start' : 'px-0 justify-center'">
                <i data-lucide="user" class="sidebar-icon mr-3 h-5 w-5 shrink-0 transition-all duration-200"></i>
                <span class="sidebar-text truncate whitespace-nowrap" x-show="isExpanded">Profil Akun</span>
            </a>

        </nav>
    </div>
</aside>
