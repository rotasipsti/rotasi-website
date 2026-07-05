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
        <!-- Navbar/Header -->
        <header :class="[
            'fixed top-0 w-full z-50 transition-all duration-300',
            scrolled ? 'bg-background/95 backdrop-blur-sm shadow-md' : 'bg-transparent'
        ]">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="/" class="flex items-center gap-2">
                        <img src="/rotasi logo.png" alt="ROTASI Logo" class="h-10 w-auto" />
                        <span class="font-bebas-neue text-2xl tracking-wider transition-colors"
                              :class="isHomePage ? (scrolled ? 'text-foreground' : 'text-white') : 'text-foreground'">
                            ROTASI
                        </span>
                    </a>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="/" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Beranda</a>
                        <a href="/tentang" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Tentang</a>
                        <a href="/struktur" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Struktur</a>
                        <a href="/tahapan" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Tahapan</a>
                        <a href="/galeri" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Galeri</a>
                        <a href="/download" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Download</a>
                        <a href="/kontak" class="text-sm font-medium transition-colors hover:text-primary"
                           :class="isHomePage ? (scrolled ? 'text-foreground/80' : 'text-white/80') : 'text-foreground/80'">Kontak</a>

                        @auth
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 flex items-center gap-2">
                                    <i data-lucide="user" class="h-4 w-4"></i>
                                    {{ Auth::user()->name }}
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </button>
                                <div x-show="open"
                                     x-transition.opacity.duration.200ms
                                     class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-card border border-border/50 overflow-hidden z-50" style="display: none;">
                                    <div class="py-1">
                                        <a href="/dashboard" class="block px-4 py-2 text-sm hover:bg-accent">Dashboard</a>
                                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-accent text-red-600">
                                                <i data-lucide="log-out" class="h-4 w-4 inline mr-2"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 flex items-center gap-2">
                                    Daftar
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </button>
                                <div x-show="open"
                                     x-transition.opacity.duration.200ms
                                     class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-card border border-border/50 overflow-hidden z-50" style="display: none;">
                                    <div class="py-1">
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-accent">Register</a>
                                        @endif
                                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-accent">Login</a>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </nav>

                    <!-- Mobile Menu Button -->
                    <button @click="isOpen = !isOpen" class="md:hidden transition-colors p-2"
                            :class="isHomePage ? (scrolled ? 'text-foreground' : 'text-white') : 'text-foreground'">
                        <i data-lucide="menu" class="h-6 w-6" x-show="!isOpen"></i>
                        <i data-lucide="x" class="h-6 w-6" x-show="isOpen" style="display: none;"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <nav x-show="isOpen" x-transition.opacity
                 class="md:hidden bg-background/95 backdrop-blur-sm border-t border-border/50 py-4" style="display: none;">
                <div class="container mx-auto px-4 flex flex-col gap-4">
                    <a href="/" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Beranda</a>
                    <a href="/tentang" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Tentang</a>
                    <a href="/struktur" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Struktur</a>
                    <a href="/tahapan" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Tahapan</a>
                    <a href="/galeri" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Galeri</a>
                    <a href="/download" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Download</a>
                    <a href="/kontak" class="text-sm font-medium py-2 text-foreground/80 hover:text-primary transition-colors">Kontak</a>

                    @auth
                        <div class="border-t border-border/50 pt-2 mt-2">
                            <p class="text-xs font-semibold mb-1 text-foreground/60">User</p>
                            <a href="/dashboard" class="block py-2 text-sm hover:text-primary">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 text-sm hover:text-primary text-red-600">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="border-t border-border/50 pt-2 mt-2">
                            <p class="text-xs font-semibold mb-1 text-foreground/60">Daftar</p>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block py-2 text-sm hover:text-primary">Register</a>
                            @endif
                            <a href="{{ route('login') }}" class="block py-2 text-sm hover:text-primary">Login</a>
                        </div>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-card border-t border-border/50 pt-12 pb-6 {{ isset($isAdminLayout) && $isAdminLayout ? 'md:ml-64' : '' }}">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="/rotasi logo.png" alt="ROTASI Logo" class="h-12 w-auto" />
                            <div>
                                <h3 class="font-bebas-neue text-2xl tracking-wider">ROTASI</h3>
                                <p class="text-xs text-muted-foreground">Regenerasi dan Orientasi Mahasiswa PSTI</p>
                            </div>
                        </div>
                        <p class="text-sm text-muted-foreground mb-4">
                            Platform kaderisasi ROTASI yang dinaungi oleh HIMA PSTI UPI untuk membentuk karakter dan melanjutkan
                            perjuangan PSTI.
                        </p>
                        <div class="flex gap-4">
                            <a href="https://www.instagram.com/rotasipsti/" class="text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="instagram" class="h-5 w-5"></i>
                                <span class="sr-only">Instagram</span>
                            </a>
                            <a href="https://www.youtube.com/@rotasipsti" class="text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="youtube" class="h-5 w-5"></i>
                                <span class="sr-only">YouTube</span>
                            </a>
                            <a href="mailto:{{ \App\Models\PageContent::where('key', 'contact_email')->value('value') ?? 'himapstipwk@upi.edu' }}" class="text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="mail" class="h-5 w-5"></i>
                                <span class="sr-only">Email</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bebas-neue text-xl mb-4">Tautan Cepat</h3>
                        <ul class="space-y-2">
                            <li><a href="/tentang" class="text-sm text-muted-foreground hover:text-primary transition-colors">Tentang ROTASI</a></li>
                            <li><a href="/struktur" class="text-sm text-muted-foreground hover:text-primary transition-colors">Struktur Panitia</a></li>
                            <li><a href="/tahapan" class="text-sm text-muted-foreground hover:text-primary transition-colors">Alur & Tahapan</a></li>
                            <li><a href="{{ route('register') }}" class="text-sm text-muted-foreground hover:text-primary transition-colors">Pendaftaran</a></li>
                            <li><a href="/galeri" class="text-sm text-muted-foreground hover:text-primary transition-colors">Galeri & Dokumentasi</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bebas-neue text-xl mb-4">Kontak</h3>
                        <ul class="space-y-3">
                            <li class="flex gap-3 text-sm text-muted-foreground">
                                <i data-lucide="map-pin" class="h-5 w-5 text-primary flex-shrink-0"></i>
                                <span>{{ \App\Models\PageContent::where('key', 'contact_address')->value('value') ?? 'Kampus UPI Purwakarta, Jl. Veteran No.8, Nagri Kaler, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41115' }}</span>
                            </li>
                            <li class="flex gap-3 text-sm text-muted-foreground">
                                <i data-lucide="phone" class="h-5 w-5 text-primary flex-shrink-0"></i>
                                <span>{{ \App\Models\PageContent::where('key', 'contact_phone')->value('value') ?? '+62 812-9220-1859' }}</span>
                            </li>
                            <li class="flex gap-3 text-sm text-muted-foreground">
                                <i data-lucide="mail" class="h-5 w-5 text-primary flex-shrink-0"></i>
                                <span>{{ \App\Models\PageContent::where('key', 'contact_email')->value('value') ?? 'himapstipwk@upi.edu' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-border/50 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <img src="/HIMA-PSTI.svg" alt="HIMA PSTI UPI Logo" class="h-8 w-auto" />
                        <span class="text-xs text-muted-foreground">Dinaungi oleh HIMA PSTI UPI</span>
                    </div>
                    <p class="text-xs text-muted-foreground">&copy; {{ date('Y') }} ROTASI - HIMA PSTI UPI. All rights reserved.</p>
                </div>
            </div>
        </footer>
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
