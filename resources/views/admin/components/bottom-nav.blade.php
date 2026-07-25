<nav class="fixed bottom-0 left-0 right-0 bg-card border-t border-border/50 z-50 md:hidden flex justify-between px-2 overflow-x-auto" style="scrollbar-width: none; -ms-overflow-style: none; padding-bottom: env(safe-area-inset-bottom);">
    <style>
        nav::-webkit-scrollbar {
            display: none;
        }
    </style>
    
    <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('dashboard*') || request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
        <i data-lucide="layout-dashboard" class="h-5 w-5 mb-1"></i>
        <span class="text-[10px] font-medium whitespace-nowrap">Dashboard</span>
    </a>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.cms.index') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('admin.cms.*') && !request()->routeIs('admin.cms.downloads.*') && !request()->routeIs('admin.cms.banners.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="globe" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">CMS</span>
        </a>
        <a href="{{ route('admin.cms.downloads.index') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('admin.cms.downloads.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Unduhan</span>
        </a>
        <a href="{{ route('admin.cms.banners.index') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('admin.cms.banners.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="image" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Banner</span>
        </a>
        <a href="{{ route('admin.passwords.index') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('admin.passwords.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="key" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Password</span>
        </a>
        <a href="{{ route('admin.approvals.index') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('admin.approvals.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="user-check" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Persetujuan</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('admin.users.*') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="users" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Akun</span>
        </a>
    @elseif(auth()->user()->role === 'mentor')
        <a href="{{ route('mentor.peserta') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('mentor.peserta') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="users" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Peserta</span>
        </a>
        <a href="{{ route('mentor.approvals') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('mentor.approvals') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="user-check" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Persetujuan</span>
        </a>
        <a href="{{ route('mentor.submissions') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('mentor.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="check-square" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Submission</span>
        </a>
        <a href="{{ route('mentor.exit.history') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('mentor.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="history" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Izin</span>
        </a>
        <a href="{{ route('shared.downloads') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Dokumen</span>
        </a>
    @elseif(auth()->user()->role === 'acara')
        <a href="{{ route('acara.tasks') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('acara.tasks') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="file-text" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Tugas</span>
        </a>
        <a href="{{ route('acara.submissions') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('acara.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download-cloud" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Pengumpulan</span>
        </a>
        <a href="{{ route('acara.exit.history') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('acara.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="history" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Izin</span>
        </a>
        <a href="{{ route('shared.downloads') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Dokumen</span>
        </a>
    @elseif(auth()->user()->role === 'peserta')
        <a href="{{ route('peserta.tasks') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('peserta.tasks') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="book-open" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Tugas</span>
        </a>
        <a href="{{ route('peserta.submissions') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('peserta.submissions') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="upload" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Pengumpulan</span>
        </a>
        <a href="{{ route('shared.downloads') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Dokumen</span>
        </a>
    @elseif(auth()->user()->role === 'keamanan')
        <a href="{{ route('keamanan.exit.scanner') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('keamanan.exit.scanner') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="scan" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Scanner</span>
        </a>
        <a href="{{ route('keamanan.exit.history') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('keamanan.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="history" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Izin</span>
        </a>
        <a href="{{ route('shared.downloads') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Dokumen</span>
        </a>
    @elseif(auth()->user()->role === 'panitia')
        <a href="{{ route('panitia.exit.history') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('panitia.exit.history') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="history" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Izin</span>
        </a>
        <a href="{{ route('shared.downloads') }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs('shared.downloads') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
            <i data-lucide="download" class="h-5 w-5 mb-1"></i>
            <span class="text-[10px] font-medium whitespace-nowrap">Dokumen</span>
        </a>
    @endif

    @php
        $role = auth()->user()->role;
        $profileRouteName = 'peserta.profile.edit';
        if ($role === 'admin') $profileRouteName = 'admin.profile.edit';
        elseif ($role === 'mentor') $profileRouteName = 'mentor.profile.edit';
        elseif ($role === 'acara') $profileRouteName = 'acara.profile.edit';
        elseif ($role === 'keamanan') $profileRouteName = 'keamanan.profile.edit';
        elseif ($role === 'panitia') $profileRouteName = 'panitia.profile.edit';
    @endphp
    <a href="{{ route($profileRouteName) }}" class="flex-shrink-0 flex flex-col items-center justify-center w-20 py-2 {{ request()->routeIs($profileRouteName) ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}">
        <i data-lucide="user" class="h-5 w-5 mb-1"></i>
        <span class="text-[10px] font-medium whitespace-nowrap">Profil</span>
    </a>
</nav>
