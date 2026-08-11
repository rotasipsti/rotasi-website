<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? (auth()->check() ? ucfirst(auth()->user()->role) . ' Dashboard' : 'Dashboard') }} - {{ config('app.name', 'ROTASI 2025') }}</title>

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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground overflow-x-hidden min-h-screen flex flex-col pb-16 md:pb-0" x-data="{ sidebarOpen: false }">
    
    <!-- Admin Navbar -->
    @include('admin.components.navbar')

    <div class="flex flex-1 pt-16">
        <!-- Admin Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col w-full md:ml-64 transition-all duration-300 min-h-[calc(100vh-4rem)]">
            
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
            </main>
            
        </div>
    </div>

    <!-- Bottom Navigation for Mobile -->
    @include('admin.components.bottom-nav')

    <!-- Init Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
    @livewireScripts
</body>
</html>
