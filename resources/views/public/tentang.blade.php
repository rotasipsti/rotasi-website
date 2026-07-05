@extends('layouts.public')

@section('content')
<section class="pt-32 pb-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center font-bebas-neue tracking-wider">TENTANG ROTASI</h1>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="flex flex-col md:flex-row gap-8 items-center mb-12">
                <div class="md:w-1/3 flex justify-center">
                    <img src="/rotasi logo.png" alt="ROTASI Logo" class="h-48 w-auto" />
                </div>
                <div class="md:w-2/3">
                    <div class="whitespace-pre-wrap text-lg text-muted-foreground">{{ \App\Models\PageContent::where('key', 'about_main_desc')->value('value') ?? 'ROTASI (Regenerasi dan Orientasi Mahasiswa PSTI) adalah program kaderisasi yang diselenggarakan oleh Departemen Pengembangan Sumber Daya Organisasi (PSDO) Himpunan Mahasiswa Pendidikan Sistem dan Teknologi Informasi (HIMA PSTI) Universitas Pendidikan Indonesia.

Program ini dirancang untuk memperkenalkan mahasiswa baru dengan lingkungan kampus, program studi, dan nilai-nilai yang dijunjung tinggi oleh PSTI UPI. ROTASI bertujuan untuk membentuk karakter mahasiswa yang loyal, progresif, tangguh, dan memiliki solidaritas tinggi.' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center font-bebas-neue tracking-wider">SEJARAH DAN FILOSOFI</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="prose prose-invert max-w-none text-muted-foreground space-y-4 whitespace-pre-wrap">
                {{ \App\Models\PageContent::where('key', 'about_history')->value('value') ?? 'Dari awal tahun Prodi PSTI berdiri yaitu tahun 2018, Awal mula nama kaderisasi ini adalah Masa Pengenalan Prodi PSTI (MANPRO PSTI) dan diubah menjadi ROTASI pada tahun 2023. ROTASI (Regenerasi dan Orientasi Mahasiswa PSTI) adalah program kaderisasi yang diselenggarakan untuk mahasiswa baru prodi PSTI UPI. Program ini dirancang untuk memperkenalkan mahasiswa baru dengan lingkungan kampus, program studi, dan nilai-nilai yang dijunjung tinggi oleh PSTI UPI.

Nama "ROTASI" dipilih sebagai simbol perputaran dan regenerasi yang berkelanjutan. Seperti bumi yang berotasi pada porosnya, mahasiswa PSTI diharapkan dapat terus bergerak maju dengan tetap berpegang pada nilai-nilai inti yang menjadi landasan program studi.

Filosofi ROTASI didasarkan pada prinsip keberlanjutan dan pengembangan. Setiap angkatan mahasiswa baru dipersiapkan untuk menjadi penerus yang akan membawa program studi ke arah yang lebih baik, sambil tetap menghormati tradisi dan nilai-nilai yang telah dibangun oleh generasi sebelumnya.

Setiap tahun, ROTASI mengangkat tema yang berbeda namun tetap berlandaskan pada nilai-nilai inti PSTI. Tema-tema ini dirancang untuk merespons perkembangan teknologi dan pendidikan, serta tantangan yang dihadapi oleh mahasiswa di era digital.' }}
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center font-bebas-neue tracking-wider">NILAI-NILAI DASAR</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="grid sm:grid-cols-2 gap-6">
                <!-- Inisiatif -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-primary/20 p-3 rounded-full">
                            <i data-lucide="shield" class="h-6 w-6 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Models\PageContent::where('key', 'about_val_1_title')->value('value') ?? 'Inisiatif' }}</h3>
                    </div>
                    <p class="text-muted-foreground">
                        {{ \App\Models\PageContent::where('key', 'about_val_1_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_val_inisiatif')->value('value') ?? 'Kemampuan untuk proaktif, berani mengambil tindakan, dan mencari solusi tanpa menunggu perintah. Mahasiswa PSTI diharapkan mampu memulai perubahan positif di lingkungan sekitarnya.' }}
                    </p>
                </div>
                <!-- Tangguh -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-primary/20 p-3 rounded-full">
                            <i data-lucide="users" class="h-6 w-6 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Models\PageContent::where('key', 'about_val_2_title')->value('value') ?? 'Tangguh' }}</h3>
                    </div>
                    <p class="text-muted-foreground">
                        {{ \App\Models\PageContent::where('key', 'about_val_2_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_val_tangguh')->value('value') ?? 'Ketahanan mental dan fisik dalam menghadapi tantangan, serta tidak mudah menyerah. Mahasiswa PSTI dilatih untuk tetap tegar dan beradaptasi dalam berbagai situasi.' }}
                    </p>
                </div>
                <!-- Beretika -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-primary/20 p-3 rounded-full">
                            <i data-lucide="lightbulb" class="h-6 w-6 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Models\PageContent::where('key', 'about_val_3_title')->value('value') ?? 'Beretika' }}</h3>
                    </div>
                    <p class="text-muted-foreground">
                        {{ \App\Models\PageContent::where('key', 'about_val_3_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_val_beretika')->value('value') ?? 'Menjunjung tinggi nilai moral, sopan santun, dan integritas dalam setiap tindakan. Mahasiswa PSTI diharapkan mampu bersikap jujur, adil, dan bertanggung jawab.' }}
                    </p>
                </div>
                <!-- Inovatif -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-primary/20 p-3 rounded-full">
                            <i data-lucide="heart" class="h-6 w-6 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Models\PageContent::where('key', 'about_val_4_title')->value('value') ?? 'Inovatif' }}</h3>
                    </div>
                    <p class="text-muted-foreground">
                        {{ \App\Models\PageContent::where('key', 'about_val_4_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_val_inovatif')->value('value') ?? 'Kreatif dalam berpikir dan berani mencoba hal baru untuk menciptakan perubahan yang lebih baik. Mahasiswa PSTI didorong untuk selalu mencari ide-ide segar dan solusi inovatif.' }}
                    </p>
                </div>
                <!-- Kooperatif -->
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-primary/20 p-3 rounded-full">
                            <i data-lucide="users" class="h-6 w-6 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Models\PageContent::where('key', 'about_val_5_title')->value('value') ?? 'Kooperatif' }}</h3>
                    </div>
                    <p class="text-muted-foreground">
                        {{ \App\Models\PageContent::where('key', 'about_val_5_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_val_kooperatif')->value('value') ?? 'Mampu bekerja sama, menghargai pendapat, dan membangun kolaborasi yang harmonis dengan sesama mahasiswa maupun pihak lain untuk mencapai tujuan bersama.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center font-bebas-neue tracking-wider">TUJUAN KADERISASI</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="space-y-6">
                <!-- 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-maroon rounded-full flex items-center justify-center text-white font-bold">1</div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">{{ \App\Models\PageContent::where('key', 'about_goal_1_title')->value('value') ?? 'Pengenalan Lingkungan' }}</h3>
                        <p class="text-muted-foreground">
                            {{ \App\Models\PageContent::where('key', 'about_goal_1_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_goal_1')->value('value') ?? 'Memperkenalkan mahasiswa baru dengan lingkungan kampus, fasilitas, dan sumber daya yang tersedia di UPI dan program studi PSTI.' }}
                        </p>
                    </div>
                </div>
                <!-- 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-maroon rounded-full flex items-center justify-center text-white font-bold">2</div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">{{ \App\Models\PageContent::where('key', 'about_goal_2_title')->value('value') ?? 'Pembentukan Karakter' }}</h3>
                        <p class="text-muted-foreground">
                            {{ \App\Models\PageContent::where('key', 'about_goal_2_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_goal_2')->value('value') ?? 'Membentuk karakter mahasiswa yang sesuai dengan nilai-nilai PSTI, termasuk loyalitas, progresivitas, tangguh, dan solidaritas.' }}
                        </p>
                    </div>
                </div>
                <!-- 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-maroon rounded-full flex items-center justify-center text-white font-bold">3</div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">{{ \App\Models\PageContent::where('key', 'about_goal_3_title')->value('value') ?? 'Pengembangan Keterampilan' }}</h3>
                        <p class="text-muted-foreground">
                            {{ \App\Models\PageContent::where('key', 'about_goal_3_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_goal_3')->value('value') ?? 'Mengembangkan keterampilan akademik dan non-akademik yang dibutuhkan untuk sukses dalam program studi dan karir di masa depan.' }}
                        </p>
                    </div>
                </div>
                <!-- 4 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-maroon rounded-full flex items-center justify-center text-white font-bold">4</div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">{{ \App\Models\PageContent::where('key', 'about_goal_4_title')->value('value') ?? 'Membangun Jaringan' }}</h3>
                        <p class="text-muted-foreground">
                            {{ \App\Models\PageContent::where('key', 'about_goal_4_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_goal_4')->value('value') ?? 'Memfasilitasi pembentukan jaringan dan hubungan antara mahasiswa baru, senior, alumni, dan dosen PSTI.' }}
                        </p>
                    </div>
                </div>
                <!-- 5 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-maroon rounded-full flex items-center justify-center text-white font-bold">5</div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">{{ \App\Models\PageContent::where('key', 'about_goal_5_title')->value('value') ?? 'Menjaga Tradisi' }}</h3>
                        <p class="text-muted-foreground">
                            {{ \App\Models\PageContent::where('key', 'about_goal_5_desc')->value('value') ?? \App\Models\PageContent::where('key', 'about_goal_5')->value('value') ?? 'Melestarikan dan mengembangkan tradisi PSTI yang telah dibangun selama bertahun-tahun, sambil tetap beradaptasi dengan perkembangan zaman.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hima-psti" class="py-16 bg-card">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center font-bebas-neue tracking-wider">HIMA PSTI UPI</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="flex flex-col md:flex-row gap-8 items-center">
                <div class="md:w-1/3 flex justify-center">
                    <img src="/HIMA-PSTI.svg" alt="HIMA PSTI Logo" class="h-48 w-auto" />
                </div>
                <div class="md:w-2/3">
                    <div class="whitespace-pre-wrap text-lg text-muted-foreground">{{ \App\Models\PageContent::where('key', 'about_hima')->value('value') ?? 'Himpunan Mahasiswa Pendidikan Sistem dan Teknologi Informasi (HIMA PSTI) UPI adalah organisasi mahasiswa yang mewadahi aspirasi dan kegiatan mahasiswa PSTI UPI.

HIMA PSTI UPI berperan sebagai pengampu kegiatan ROTASI dan bertanggung jawab atas keberlangsungan program kaderisasi ini dari tahun ke tahun. Melalui ROTASI, HIMA PSTI UPI berupaya untuk membentuk karakter mahasiswa PSTI yang sesuai dengan nilai-nilai dan visi misi program studi.

Seluruh rangkaian kegiatan ROTASI berada di bawah pengawasan dan bimbingan HIMA PSTI UPI, dengan dukungan dari dosen dan pimpinan program studi.' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
