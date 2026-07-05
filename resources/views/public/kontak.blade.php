@extends('layouts.public')

@section('content')
<section class="pt-32 pb-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center">KONTAK KAMI</h1>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <p class="text-lg text-center mb-8">
                Hubungi kami untuk informasi lebih lanjut tentang ROTASI atau kirimkan pertanyaan Anda melalui formulir di
                bawah ini.
            </p>
        </div>
    </div>
</section>

<section class="py-16" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Kontak Info -->
                <div>
                    <h2 class="text-2xl font-bold mb-6">Informasi Kontak</h2>

                    <div class="space-y-6">
                        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                            <div class="p-6">
                                <div class="flex gap-4">
                                    <i data-lucide="map-pin" class="h-6 w-6 text-primary flex-shrink-0"></i>
                                    <div>
                                        <h3 class="font-bold mb-1">Alamat</h3>
                                        <p class="text-muted-foreground">
                                            Kampus UPI Purwakarta, Jl. Veteran No.8, Nagri Kaler, Kec. Purwakarta, Kabupaten Purwakarta,
                                            Jawa Barat 41115
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                            <div class="p-6">
                                <div class="flex gap-4">
                                    <i data-lucide="phone" class="h-6 w-6 text-primary flex-shrink-0"></i>
                                    <div>
                                        <h3 class="font-bold mb-1">Telepon</h3>
                                        <p class="text-muted-foreground">+62 812-9220-1859</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                            <div class="p-6">
                                <div class="flex gap-4">
                                    <i data-lucide="mail" class="h-6 w-6 text-primary flex-shrink-0"></i>
                                    <div>
                                        <h3 class="font-bold mb-1">Email</h3>
                                        <p class="text-muted-foreground">rotasi@psti.upi.edu</p>
                                        <p class="text-muted-foreground">himapsti@upi.edu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-4">Media Sosial</h3>
                        <div class="flex flex-wrap gap-4">
                            <a href="https://www.instagram.com/rotasipsti/" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="instagram" class="h-5 w-5"></i>
                                <span>@rotasipsti</span>
                            </a>
                            <a href="https://www.instagram.com/himapstiupi/" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="instagram" class="h-5 w-5"></i>
                                <span>@himapstiupi</span>
                            </a>
                            <a href="https://www.youtube.com/@rotasipsti" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="youtube" class="h-5 w-5"></i>
                                <span>ROTASI PSTI</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Kontak -->
                <div x-data="{
                    loading: false,
                    submitted: false,
                    submitForm(e) {
                        e.preventDefault();
                        this.loading = true;
                        setTimeout(() => {
                            this.loading = false;
                            this.submitted = true;
                            e.target.reset();
                            
                            setTimeout(() => {
                                this.submitted = false;
                            }, 5000);
                        }, 1500);
                    }
                }">
                    <h2 class="text-2xl font-bold mb-6">Kirim Pesan</h2>

                    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                        <div class="p-6">
                            <!-- Pesan Sukses -->
                            <div x-show="submitted" style="display: none;" class="mb-4 rounded-lg bg-green-500/15 border border-green-500/20 p-4 text-green-600 dark:text-green-400">
                                <h4 class="font-bold">Pesan Terkirim</h4>
                                <p class="text-sm">Terima kasih telah menghubungi kami. Kami akan segera merespons pesan Anda.</p>
                            </div>

                            <form @submit="submitForm" class="space-y-4">
                                <div class="space-y-2">
                                    <label for="nama" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Nama Lengkap</label>
                                    <input id="nama" type="text" placeholder="Masukkan nama lengkap" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                                </div>

                                <div class="space-y-2">
                                    <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Email</label>
                                    <input id="email" type="email" placeholder="Masukkan email" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                                </div>

                                <div class="space-y-2">
                                    <label for="subjek" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Subjek</label>
                                    <input id="subjek" type="text" placeholder="Masukkan subjek pesan" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                                </div>

                                <div class="space-y-2">
                                    <label for="pesan" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Pesan</label>
                                    <textarea id="pesan" placeholder="Tulis pesan Anda di sini" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-32"></textarea>
                                </div>

                                <button type="submit" :disabled="loading" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-maroon hover:bg-maroon-light text-primary-foreground h-10 px-4 py-2">
                                    <span x-show="!loading">Kirim Pesan</span>
                                    <span x-show="loading" style="display: none;">Mengirim...</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Lokasi Kami</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="aspect-[16/9] bg-muted rounded-lg overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15855.464624480175!2d107.42624673190394!3d-6.538581645024822!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690e68a1406c01%3A0xa66f34eb29c41198!2sIndonesia%20University%20Of%20Education%2C%20Campus%20Purwakarta!5e0!3m2!1sen!2sid!4v1751021050732!5m2!1sen!2sid"
                    width="100%"
                    height="100%"
                    style="border: 0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi UPI Purwakarta"
                    class="w-full h-full"
                ></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
