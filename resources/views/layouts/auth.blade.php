<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROTASI - Regenerasi dan Orientasi Mahasiswa PSTI UPI</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        :root {
            --font-inter: 'Inter', sans-serif;
            --font-bebas-neue: 'Bebas Neue', sans-serif;
        }
    </style>

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="font-sans bg-background theme-transition text-foreground antialiased" x-data="{
    scrolled: false,
    isOpen: false,
    isHomePage: window.location.pathname === '/',
    init() {
        this.scrolled = window.scrollY > 20;
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20;
        });
    }
}">
    <div class="flex min-h-screen flex-col">
        <!-- Main Content -->
        <main class="flex-1 flex flex-col justify-center">
            @yield('content')
        </main>
    </div>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('error_alert'))
                Swal.fire({
                    title: '<h2 class="text-xl font-bold font-inter mt-2">Akses Ditolak</h2>',
                    html: '<p class="text-sm mt-1">{{ session('error_alert') }}</p>',
                    icon: 'error',
                    confirmButtonText: 'Tutup',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                        title: 'text-foreground',
                        htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                        confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none',
                        icon: '!border-destructive !text-destructive !m-0 !mx-auto'
                    },
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                });
            @endif

            @if(session('register_success'))
                Swal.fire({
                    title: '<h2 class="text-xl font-bold font-inter mt-2">Pendaftaran Berhasil!</h2>',
                    html: '<p class="text-sm mt-1">{!! session("register_message") !!}</p>',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                        title: 'text-foreground',
                        htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                        confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-8 py-2 rounded-md text-sm font-medium transition-colors border-none',
                        icon: '!border-green-500 !text-green-500 !m-0 !mx-auto'
                    },
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ session('login_route') }}";
                    }
                });
            @endif
        });
    </script>
    
    @yield('scripts')
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 1000,
                offset: 50,
                easing: 'ease-out-cubic',
                delay: 100,
            });
        });
    </script>
</body>
</html>
