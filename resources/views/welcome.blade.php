@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-16 overflow-hidden">
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 brightness-50">
            <source src="https://content.rotasipsti.id/videos/logo.mp4" type="video/mp4" />
        </video>
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="container mx-auto px-4 py-12 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <div class="mb-6 flex justify-center">
                    <img src="/rotasi logo.png" alt="ROTASI Logo" class="h-32 w-auto animate-fade-in" />
                </div>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-4 animate-fade-up text-white font-bebas-neue tracking-wider">
                    REGENERASI DAN ORIENTASI MAHASISWA PSTI
                </h1>
                <p class="text-xl md:text-2xl text-white/80 mb-8 animate-fade-up" style="animation-delay: 200ms;">
                    Membentuk Karakter, Melanjutkan Perjuangan PSTI
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-up" style="animation-delay: 400ms;">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium h-11 px-8 bg-maroon text-white hover:bg-maroon-light transition-colors">
                        Daftar Sekarang
                    </a>
                    <a href="/tentang" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium h-11 px-8 border border-white text-white hover:bg-white hover:text-black transition-colors">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Countdown Section -->
    <section class="py-16 bg-card">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 font-bebas-neue tracking-wider">{{ $countdownTitle }}</h2>
                
                <!-- Simple Countdown via JS -->
                <div id="countdown-timer" class="flex justify-center gap-4 text-center mt-8 mb-12">
                    <div class="flex flex-col items-center justify-center w-24 h-24 rounded-md" style="background-color: #151515; border: 1px solid #3f1616;">
                        <div class="text-4xl font-bold text-primary" id="cd-days">0</div>
                        <div class="text-sm mt-1 text-muted-foreground">Hari</div>
                    </div>
                    <div class="flex flex-col items-center justify-center w-24 h-24 rounded-md" style="background-color: #151515; border: 1px solid #3f1616;">
                        <div class="text-4xl font-bold text-primary" id="cd-hours">0</div>
                        <div class="text-sm mt-1 text-muted-foreground">Jam</div>
                    </div>
                    <div class="flex flex-col items-center justify-center w-24 h-24 rounded-md" style="background-color: #151515; border: 1px solid #3f1616;">
                        <div class="text-4xl font-bold text-primary" id="cd-minutes">0</div>
                        <div class="text-sm mt-1 text-muted-foreground">Menit</div>
                    </div>
                    <div class="flex flex-col items-center justify-center w-24 h-24 rounded-md" style="background-color: #151515; border: 1px solid #3f1616;">
                        <div class="text-4xl font-bold text-primary" id="cd-seconds">0</div>
                        <div class="text-sm mt-1 text-muted-foreground">Detik</div>
                    </div>
                </div>

                <p class="mt-8 text-muted-foreground">
                    Persiapkan dirimu untuk menjadi bagian dari regenerasi PSTI UPI
                </p>
            </div>
        </div>
    </section>

    <!-- About Section Preview -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 font-bebas-neue tracking-wider">TENTANG ROTASI</h2>
                    <div class="w-24 h-1 bg-primary mb-6"></div>
                    <p class="text-muted-foreground mb-6">
                        ROTASI adalah program kaderisasi yang bertujuan untuk membentuk karakter mahasiswa PSTI UPI yang loyal,
                        progresif, kritis, dan memiliki solidaritas tinggi. Melalui berbagai kegiatan dan tahapan, ROTASI
                        mempersiapkan mahasiswa baru untuk menjadi bagian dari keluarga besar PSTI UPI.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-primary/20 p-2 rounded-full">
                                <i data-lucide="users" class="h-5 w-5 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Inisiatif</h3>
                                <p class="text-sm text-muted-foreground">
                                    Mahasiswa PSTI diharapkan mampu memulai perubahan positif di lingkungan sekitarnya.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="bg-primary/20 p-2 rounded-full">
                                <i data-lucide="award" class="h-5 w-5 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Tangguh</h3>
                                <p class="text-sm text-muted-foreground">
                                    Mahasiswa PSTI dilatih untuk tetap tegar dan beradaptasi dalam berbagai situasi.
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="/tentang" class="inline-flex items-center mt-8 bg-maroon hover:bg-maroon-light text-white px-6 py-2.5 rounded-md font-medium transition-colors">
                        Selengkapnya <i data-lucide="chevron-right" class="ml-2 h-4 w-4"></i>
                    </a>
                </div>
                <div class="relative h-[400px] rounded-lg overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent z-10"></div>
                    <img src="./rotasii2024.jpg" alt="Kegiatan ROTASI 2024" class="absolute inset-0 w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Preview -->
    <section class="py-16 bg-card">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 font-bebas-neue tracking-wider">ALUR & TAHAPAN KEGIATAN</h2>
                <div class="w-24 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-muted-foreground max-w-2xl mx-auto">
                    ROTASI terdiri dari beberapa tahapan yang dirancang untuk membangun karakter dan pengetahuan mahasiswa
                    baru PSTI UPI
                </p>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="timeline-container relative">
                    <!-- CSS for timeline-container is in globals.css which we ported -->
                    
                    <div class="timeline-item">
                        <div class="timeline-content w-full md:w-5/12 bg-background p-6 rounded-lg shadow">
                            <h3 class="font-bold text-xl">Pra-ROTASI</h3>
                            <div class="flex items-center gap-2 text-sm text-primary mb-2">
                                <i data-lucide="calendar" class="h-4 w-4"></i>
                                <span>September 2025</span>
                            </div>
                            <p class="text-muted-foreground">Persiapan dan pengenalan awal</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-content w-full md:w-5/12 bg-background p-6 rounded-lg shadow">
                            <h3 class="font-bold text-xl">ROTASI Tahap I</h3>
                            <div class="flex items-center gap-2 text-sm text-primary mb-2">
                                <i data-lucide="calendar" class="h-4 w-4"></i>
                                <span>Oktober 2025</span>
                            </div>
                            <p class="text-muted-foreground">Pengenalan lingkungan kampus dan program studi</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-content w-full md:w-5/12 bg-background p-6 rounded-lg shadow">
                            <h3 class="font-bold text-xl">ROTASI Tahap II</h3>
                            <div class="flex items-center gap-2 text-sm text-primary mb-2">
                                <i data-lucide="calendar" class="h-4 w-4"></i>
                                <span>Oktober 2025</span>
                            </div>
                            <p class="text-muted-foreground">Pembentukan karakter dan nilai-nilai dasar</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center bg-maroon hover:bg-maroon-light text-white px-6 py-2.5 rounded-md font-medium transition-colors">
                    Lihat Timeline Lengkap <i data-lucide="chevron-right" class="ml-2 h-4 w-4"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 font-bebas-neue tracking-wider">FITUR WEBSITE ROTASI</h2>
                <div class="w-24 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-muted-foreground max-w-2xl mx-auto">
                    Platform digital yang mendukung seluruh kegiatan kaderisasi ROTASI
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:border-primary/50 transition-colors">
                    <div class="p-6">
                        <div class="mb-4"><i data-lucide="calendar" class="h-10 w-10 text-primary"></i></div>
                        <h3 class="text-xl font-bold mb-2">Informasi Kegiatan</h3>
                        <p class="text-muted-foreground">Jadwal lengkap dan detail setiap tahapan ROTASI</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:border-primary/50 transition-colors">
                    <div class="p-6">
                        <div class="mb-4"><i data-lucide="users" class="h-10 w-10 text-primary"></i></div>
                        <h3 class="text-xl font-bold mb-2">Pendaftaran Online</h3>
                        <p class="text-muted-foreground">Sistem pendaftaran peserta dan panitia yang terintegrasi</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:border-primary/50 transition-colors">
                    <div class="p-6">
                        <div class="mb-4"><i data-lucide="book-open" class="h-10 w-10 text-primary"></i></div>
                        <h3 class="text-xl font-bold mb-2">Manajemen Tugas</h3>
                        <p class="text-muted-foreground">Upload dan evaluasi tugas kaderisasi secara digital</p>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:border-primary/50 transition-colors">
                    <div class="p-6">
                        <div class="mb-4"><i data-lucide="award" class="h-10 w-10 text-primary"></i></div>
                        <h3 class="text-xl font-bold mb-2">Sertifikat Digital</h3>
                        <p class="text-muted-foreground">Sertifikat kelulusan yang dapat diunduh secara otomatis</p>
                    </div>
                </div>
                <!-- Feature 5 -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:border-primary/50 transition-colors">
                    <div class="p-6">
                        <div class="mb-4"><img src="/rotasi logo.png" class="h-10 w-10" alt="Logo"/></div>
                        <h3 class="text-xl font-bold mb-2">Nilai-nilai ROTASI</h3>
                        <p class="text-muted-foreground">Pengenalan filosofi dan nilai-nilai dasar kaderisasi</p>
                    </div>
                </div>
                <!-- Feature 6 -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow hover:border-primary/50 transition-colors">
                    <div class="p-6">
                        <div class="mb-4"><img src="/HIMA-PSTI.svg" class="h-10 w-10" alt="HIMA"/></div>
                        <h3 class="text-xl font-bold mb-2">Profil HIMA PSTI</h3>
                        <p class="text-muted-foreground">Informasi tentang Himpunan Mahasiswa PSTI UPI</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-maroon">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white font-bebas-neue tracking-wider">BERGABUNGLAH DENGAN ROTASI</h2>
                <p class="text-white/80 mb-8">
                    Jadilah bagian dari regenerasi PSTI UPI dan kembangkan potensimu bersama kami
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://www.instagram.com/rotasipsti/" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium h-11 px-8 bg-white text-maroon hover:bg-white/90 transition-colors">
                        Ikuti Instagram Kami
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium h-11 px-8 border border-white text-white hover:bg-white hover:text-black transition-colors">
                        Gabung Sebagai Peserta
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Countdown Logic
    function updateCountdown() {
        const targetDate = new Date("{{ $countdownDate }}").getTime();
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance < 0) {
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const cdDays = document.getElementById("cd-days");
        if(cdDays) {
            cdDays.innerText = days;
            document.getElementById("cd-hours").innerText = hours;
            document.getElementById("cd-minutes").innerText = minutes;
            document.getElementById("cd-seconds").innerText = seconds;
        }
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
@endsection
