@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-4" data-aos="fade-up">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                    Download <span class="text-red-600 dark:text-red-400">MyRotasi</span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                    Aplikasi resmi ROTASI untuk perangkat Android. Mudah dan praktis, akses di mana saja dan kapan saja.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ \App\Models\PageContent::where('key', 'download_app_link')->value('value') ?? 'https://content.rotasipsti.id/app/peserta/MyROTASI.apk' }}" download="MyROTASI.apk" class="inline-flex items-center justify-center whitespace-nowrap rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-12 px-8 py-4 text-lg bg-red-600 hover:bg-red-700 text-white shadow">
                        <i data-lucide="download" class="h-5 w-5 mr-2"></i>
                        Download Sekarang
                    </a>
                </div>
            </div>

            <!-- App Preview -->
            <div class="relative max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <!-- Phone Mockup -->
                    <div class="relative mx-auto">
                        <div class="w-64 h-[500px] bg-gray-800 rounded-[3rem] p-2 shadow-2xl">
                            <div class="w-full h-full bg-white rounded-[2.5rem] overflow-hidden">
                                <div class="h-8 bg-gray-200 rounded-t-[2.5rem] flex items-center justify-center">
                                    <div class="w-16 h-1 bg-gray-400 rounded-full"></div>
                                </div>
                                <div class="p-6 h-full bg-gradient-to-b from-red-50 to-white">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-black rounded-2xl mx-auto mb-4 flex items-center justify-center">
                                            <img src="/rotasi logo.png" alt="ROTASI Logo" class="w-10 h-10 object-contain" />
                                        </div>
                                        <h3 class="font-bold text-lg text-gray-900">MyRotasi</h3>
                                        <p class="text-sm text-gray-600 mt-2">Akses ROTASI di genggaman Anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="space-y-6">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            Fitur Unggulan
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-6 w-6 text-green-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Kemudahan Akses</h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">Akses informasi ROTASI di mana saja dan kapan saja</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-6 w-6 text-green-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Ringan</h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">Ukuran aplikasi kecil, ramah untuk semua perangkat Android</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-6 w-6 text-green-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Tanpa Iklan</h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">Aplikasi 100% tanpa iklan mengganggu</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-6 w-6 text-green-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Aman</h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">Aplikasi 100% aman tanpa virus</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- System Requirements Section -->
    <section class="py-20 px-4 bg-white dark:bg-gray-900" data-aos="fade-up">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                    Persyaratan Minimum Sistem
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Pastikan perangkat Anda memenuhi persyaratan minimum untuk pengalaman optimal
                </p>
            </div>

            <div class="max-w-md mx-auto">
                <!-- Android Requirements -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6 text-center space-y-1.5">
                        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                            <i data-lucide="smartphone" class="h-8 w-8 text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="font-semibold leading-none tracking-tight text-xl">Android</h3>
                        <p class="text-sm text-muted-foreground">Persyaratan untuk perangkat Android</p>
                    </div>
                    <div class="p-6 pt-0 space-y-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                            <span class="text-sm">Android 7.0 atau lebih baru</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                            <span class="text-sm">RAM minimal 1GB</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                            <span class="text-sm">Storage 10MB kosong</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                            <span class="text-sm">Koneksi internet 4G/5G atau WiFi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 px-4 bg-gray-50 dark:bg-gray-800" data-aos="fade-up">
        <div class="container mx-auto max-w-4xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    Temukan jawaban untuk pertanyaan umum tentang MyROTASI
                </p>
            </div>

            <div class="space-y-6">
                @php
                $faqs = [
                    ["q" => "Apakah MyROTASI gratis?", "a" => "Ya, MyROTASI adalah aplikasi resmi ROTASI yang dapat diunduh dan digunakan secara gratis oleh semua peserta ROTASI."],
                    ["q" => "Apakah aplikasi ini aman untuk diunduh?", "a" => "Tentu saja! MyROTASI adalah aplikasi resmi yang dikembangkan oleh tim ROTASI dan telah melalui proses pengujian keamanan. Aplikasi ini 100% aman tanpa virus."],
                    ["q" => "Apakah ada iklan dalam aplikasi?", "a" => "Tidak, aplikasi MyROTASI 100% tanpa iklan. Kami berkomitmen memberikan pengalaman yang terbaik untuk peserta ROTASI."],
                    ["q" => "Berapa ukuran file aplikasi?", "a" => "Aplikasi MyROTASI memiliki ukuran yang sangat ringan, hanya membutuhkan sekitar 10MB storage kosong. Aplikasi ini dirancang untuk ramah dengan semua perangkat Android."],
                    ["q" => "Apakah aplikasi memerlukan koneksi internet?", "a" => "Ya, MyROTASI memerlukan koneksi internet untuk sinkronisasi data dan mengakses informasi terbaru. Kami merekomendasikan koneksi 4G/5G atau WiFi untuk pengalaman terbaik."],
                    ["q" => "Bagaimana cara menginstal aplikasi?", "a" => "Setelah mengunduh file APK, buka file tersebut di perangkat Android Anda. Jika muncul peringatan keamanan, pilih \"Install from unknown sources\" atau \"Allow from this source\" untuk melanjutkan instalasi."],
                    ["q" => "Apakah aplikasi tersedia untuk perangkat iOS?", "a" => "Saat ini MyROTASI hanya tersedia untuk perangkat Android. Untuk pengguna iOS, Anda dapat mengakses ROTASI di browser Safari atau browser lainnya."],
                    ["q" => "Bagaimana jika mengalami masalah dengan aplikasi?", "a" => "Jika Anda mengalami masalah teknis atau memiliki pertanyaan tentang aplikasi, silakan hubungi tim website ROTASI melalui email hi@web.rotasipsti.id"]
                ];
                @endphp

                @foreach ($faqs as $faq)
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6 flex flex-col space-y-1.5 pb-3">
                        <h3 class="font-semibold tracking-tight text-lg text-gray-900 dark:text-white">
                            {{ $faq['q'] }}
                        </h3>
                    </div>
                    <div class="p-6 pt-0">
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ $faq['a'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
