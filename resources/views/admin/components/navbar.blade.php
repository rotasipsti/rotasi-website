<header class="fixed top-0 w-full z-50 bg-card border-b border-border/50 h-16 shadow-sm">
    <div class="flex items-center justify-between h-full px-4 md:px-6">
        
        <!-- Left side -->
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="hidden text-muted-foreground hover:text-foreground">
                <i data-lucide="menu" class="h-6 w-6"></i>
            </button>
            <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="/rotasi logo.png" alt="ROTASI Logo" class="h-8 w-auto" />
                <span class="font-bebas-neue text-2xl tracking-wider hidden sm:block text-foreground">ROTASI</span>
            </a>
        </div>

        <!-- Right side -->
        <div class="flex items-center gap-4">
            
            <div class="h-6 w-px bg-border/50 mx-2"></div>
            
            <!-- User Dropdown -->
            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="flex items-center gap-2 hover:bg-accent rounded-md py-1 px-2 transition-colors">
                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm border border-primary/30 overflow-hidden">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                    <span class="text-sm font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                    <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground"></i>
                </button>
                
                <div x-show="open" 
                     x-transition.opacity.duration.200ms
                     class="absolute right-0 top-10 mt-1 w-56 rounded-md shadow-lg bg-card border border-border/50 overflow-hidden z-50" 
                     style="display: none;">
                    <div class="p-3 border-b border-border/50 bg-muted/30">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-1">
                        @php
                            $role = auth()->user()->role;
                            $profileRouteName = 'peserta.profile.edit';
                            
                            if ($role === 'admin') {
                                $profileRouteName = 'admin.profile.edit';
                            } elseif ($role === 'mentor') {
                                $profileRouteName = 'mentor.profile.edit';
                            } elseif ($role === 'acara') {
                                $profileRouteName = 'acara.profile.edit';
                            }
                        @endphp
                        <a wire:navigate href="{{ route($profileRouteName) }}" class="flex items-center px-4 py-2 text-sm hover:bg-accent">
                            <i data-lucide="user" class="h-4 w-4 mr-2"></i> Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                            @csrf
                            <button type="submit" class="w-full flex items-center text-left px-4 py-2 text-sm hover:bg-accent text-red-500">
                                <i data-lucide="log-out" class="h-4 w-4 mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
