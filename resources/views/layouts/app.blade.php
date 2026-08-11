<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main x-data="{ navigating: false }" 
                  x-on:livewire:navigating.window="navigating = true" 
                  x-on:livewire:navigated.window="navigating = false">
                  
                <div x-show="!navigating">
                    {{ $slot }}
                </div>

                <!-- Skeleton UI (shown during navigation) -->
                <div x-show="navigating" style="display: none;" class="p-6 max-w-7xl mx-auto w-full">
                    <div class="animate-pulse flex flex-col space-y-6">
                        <div class="h-8 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="space-y-3 pt-6">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="h-32 bg-gray-200 rounded col-span-2"></div>
                                <div class="h-32 bg-gray-200 rounded col-span-1"></div>
                            </div>
                            <div class="h-24 bg-gray-200 rounded"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
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
        @livewireScripts
    </body>
</html>
