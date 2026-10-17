<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ ($_COOKIE['theme'] ?? 'dark') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? (auth()->check() ? ucfirst(auth()->user()->role) . ' Dashboard' : 'Dashboard') }} - {{ config('app.name', 'ROTASI 2025') }}</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#e11d48">
    <link rel="apple-touch-icon" href="/rotasi logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        :root {
            --font-inter: 'Inter', sans-serif;
            --font-bebas-neue: 'Bebas Neue', sans-serif;
        }
    </style>

    <!-- Theme Initialization -->
    <script>
        function getSystemTheme() {
            return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            const effective = theme === 'system' ? getSystemTheme() : theme;
            if (effective === 'light') {
                document.documentElement.classList.remove('dark');
                document.cookie = "theme=light; path=/; max-age=31536000";
            } else {
                document.documentElement.classList.add('dark');
                document.cookie = "theme=dark; path=/; max-age=31536000";
            }
        }

        const initialTheme = localStorage.getItem('theme') || 'system';
        applyTheme(initialTheme);

        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if ((localStorage.getItem('theme') || 'system') === 'system') {
                    applyTheme('system');
                }
            });
        }

        // Sidebar Initial State (prevents sliding animation on load/refresh)
        if (localStorage.getItem('sidebarOpen') === 'false') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
    <style>
        @media (min-width: 768px) {
            /* Initial load and collapsed state */
            html.sidebar-collapsed #main-content-wrapper {
                margin-left: 4rem !important;
            }
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) {
                width: 4rem !important;
            }
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) nav,
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) .sidebar-nav {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) .sidebar-link {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) .sidebar-link svg,
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) .sidebar-link i {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
            html.sidebar-collapsed #admin-sidebar:not(.sidebar-expanded) .sidebar-text {
                display: none !important;
            }

            /* Collapsed mini rail rules when NOT hovered */
            #admin-sidebar:not(.sidebar-expanded) nav,
            #admin-sidebar:not(.sidebar-expanded) .sidebar-nav {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            #admin-sidebar:not(.sidebar-expanded) .sidebar-link {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            #admin-sidebar:not(.sidebar-expanded) .sidebar-link svg,
            #admin-sidebar:not(.sidebar-expanded) .sidebar-link i {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
            #admin-sidebar:not(.sidebar-expanded) .sidebar-text {
                display: none !important;
            }

            /* Expanded sidebar rules (NORMAL or HOVER PREVIEW) */
            #admin-sidebar.sidebar-expanded nav,
            #admin-sidebar.sidebar-expanded .sidebar-nav {
                padding-left: 0.75rem !important; /* px-3 (12px) */
                padding-right: 0.75rem !important; /* px-3 (12px) */
            }
            #admin-sidebar.sidebar-expanded .sidebar-link {
                justify-content: flex-start !important;
                padding-left: 0.75rem !important; /* px-3 (12px) */
                padding-right: 0.75rem !important; /* px-3 (12px) */
            }
            #admin-sidebar.sidebar-expanded .sidebar-link svg,
            #admin-sidebar.sidebar-expanded .sidebar-link i {
                margin-right: 0.75rem !important; /* mr-3 (12px) space between icon and text */
                margin-left: 0 !important;
                flex-shrink: 0 !important;
                width: 1.25rem !important; /* 20px */
                height: 1.25rem !important; /* 20px */
            }
            #admin-sidebar.sidebar-expanded .sidebar-text {
                display: inline !important;
                white-space: nowrap !important;
                animation: sidebarTextFadeIn 0.2s ease-in-out;
            }

            @keyframes sidebarTextFadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground overflow-x-hidden min-h-screen flex flex-col pb-0" 
      x-data="{ 
          sidebarOpen: localStorage.getItem('sidebarOpen') !== null ? localStorage.getItem('sidebarOpen') === 'true' : true,
          sidebarHovered: false,
          isReady: false,
          get isExpanded() {
              return this.sidebarOpen || this.sidebarHovered;
          },
          toggleSidebar() {
              this.isReady = true;
              this.sidebarOpen = !this.sidebarOpen;
              this.sidebarHovered = false;
              localStorage.setItem('sidebarOpen', this.sidebarOpen);
              if (this.sidebarOpen) {
                  document.documentElement.classList.remove('sidebar-collapsed');
              } else {
                  document.documentElement.classList.add('sidebar-collapsed');
              }
          }
      }">
    <x-announcement />
    
    <!-- Admin Navbar -->
    @include('admin.components.navbar')

    <div class="flex flex-1 pt-16">
        <!-- Admin Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content Wrapper -->
        <div id="main-content-wrapper"
             class="flex-1 flex flex-col w-full md:ml-64 min-h-[calc(100vh-4rem)]"
             :class="{
                 'transition-all duration-300 ease-in-out': isReady,
                 'md:ml-64': sidebarOpen,
                 'md:ml-16': !sidebarOpen
             }">
            
            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-8 relative"
                  x-data="{ navigating: false }" 
                  x-on:livewire:navigating.window="navigating = true" 
                  x-on:livewire:navigated.window="navigating = false">

                <div x-show="!navigating">
                    @yield('content')
                </div>

                <!-- Skeleton UI (shown during navigation) -->
                <div x-show="navigating" style="display: none;" class="w-full">
                    <div class="animate-pulse flex flex-col space-y-6">
                        <div class="h-8 bg-muted rounded w-1/4"></div>
                        <div class="h-4 bg-muted rounded w-3/4"></div>
                        <div class="space-y-3 pt-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="h-32 bg-muted rounded"></div>
                                <div class="h-32 bg-muted rounded"></div>
                                <div class="h-32 bg-muted rounded"></div>
                                <div class="h-32 bg-muted rounded"></div>
                            </div>
                            <div class="h-48 bg-muted rounded mt-6"></div>
                        </div>
                    </div>
                </div>

                <!-- Spacer for Mobile Bottom Nav -->
                <div class="md:hidden w-full" style="height: calc(120px + env(safe-area-inset-bottom));"></div>
            </main>
            
        </div>
    </div>

    <!-- Bottom Navigation for Mobile -->
    @include('admin.components.bottom-nav')

    <!-- Init Lucide Icons & Handle Livewire Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        document.addEventListener('livewire:navigated', () => {
            // Re-initialize icons for newly loaded DOM
            lucide.createIcons();
        });
    </script>

    <!-- SweetAlert2 for nice confirm popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[onsubmit*="return confirm"]');
            forms.forEach(form => {
                const onsubmitAttr = form.getAttribute('onsubmit');
                const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                if (match) {
                    const message = match[1];
                    form.removeAttribute('onsubmit');
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '<h2 class="text-xl font-bold font-inter mt-2">Konfirmasi Tindakan</h2>',
                            html: `<p class="text-sm mt-1">${message}</p>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Lanjutkan',
                            cancelButtonText: 'Batal',
                            buttonsStyling: false,
                            customClass: {
                                popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                                title: 'text-foreground',
                                htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                                confirmButton: 'bg-destructive text-destructive-foreground hover:bg-destructive/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none',
                                cancelButton: 'bg-secondary text-secondary-foreground hover:bg-secondary/80 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none ml-3',
                                actions: '!mt-6',
                                icon: '!border-destructive !text-destructive !m-0 !mx-auto'
                            },
                            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });
        });
    </script>
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '<h2 class="text-xl font-bold font-inter mt-2">Berhasil</h2>',
                html: '<p class="text-sm mt-1">{{ session("success") }}</p>',
                icon: 'success',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                    title: 'text-foreground',
                    htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                    confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                    icon: '!border-green-500 !text-green-500 !m-0 !mx-auto'
                },
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '<h2 class="text-xl font-bold font-inter mt-2">Terjadi Kesalahan</h2>',
                html: '<p class="text-sm mt-1">{{ session("error") }}</p>',
                icon: 'error',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                    title: 'text-foreground',
                    htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                    confirmButton: 'bg-destructive text-destructive-foreground hover:bg-destructive/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                    icon: '!border-destructive !text-destructive !m-0 !mx-auto'
                },
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            });
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorHtml = '<ul class="text-sm mt-1 text-left list-disc list-inside">';
            @foreach($errors->all() as $error)
                errorHtml += '<li>{{ $error }}</li>';
            @endforeach
            errorHtml += '</ul>';

            Swal.fire({
                title: '<h2 class="text-xl font-bold font-inter mt-2">Validasi Gagal</h2>',
                html: errorHtml,
                icon: 'error',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                    title: 'text-foreground',
                    htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                    confirmButton: 'bg-destructive text-destructive-foreground hover:bg-destructive/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                    icon: '!border-destructive !text-destructive !m-0 !mx-auto'
                },
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            });
        });
    </script>
    @endif

    <script>
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            applyTheme(theme);
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: theme }));
            if (window.lucide) {
                setTimeout(() => window.lucide.createIcons(), 50);
            }
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.cookie = "theme=light; path=/; max-age=31536000";
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.cookie = "theme=dark; path=/; max-age=31536000";
            }
        }
    </script>
    
    @livewireScripts
    @stack('scripts')
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    // Registration was successful
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>

    <!-- Global PWA Onboarding Banner -->
    <x-pwa-onboarding-banner />

    <!-- Toast Login Success -->
    @if(session('login_success'))
    <div x-data="{ show: true, progress: 100 }"
         x-init="
            setTimeout(() => show = false, 3000);
            let interval = setInterval(() => {
                progress -= 1;
                if(progress <= 0) clearInterval(interval);
            }, 30);
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-full md:translate-y-0 md:translate-x-full"
         x-transition:enter-end="opacity-100 translate-y-0 md:translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 md:translate-x-0"
         x-transition:leave-end="opacity-0 -translate-y-full md:translate-y-0 md:translate-x-full"
         class="fixed top-4 left-4 right-4 z-[100] w-auto md:w-80 md:top-20 md:left-auto md:right-4 bg-card border border-border/50 rounded-xl shadow-lg overflow-hidden"
         style="display: none;"
    >
        <div class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center flex-shrink-0 text-green-500">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-foreground text-sm">Berhasil Masuk</h4>
                <p class="text-xs text-muted-foreground mt-0.5">Selamat datang kembali di ROTASI!</p>
            </div>
        </div>
        <!-- Progress Bar -->
        <div class="h-1 bg-secondary w-full">
            <div class="h-full bg-green-500 transition-all duration-75 ease-linear" :style="`width: ${progress}%`"></div>
        </div>
    </div>
    @endif
</body>
</html>
