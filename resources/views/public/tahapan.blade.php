@extends('layouts.public')

@section('content')
<section class="pt-32 pb-16 bg-card">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center">ALUR & TAHAPAN KEGIATAN</h1>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <p class="text-lg text-center mb-8">
                ROTASI terdiri dari beberapa tahapan yang dirancang untuk membangun karakter dan pengetahuan mahasiswa
                baru PSTI UPI secara bertahap dan komprehensif.
            </p>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="timeline-container">
                @foreach ($timelines as $phase)
                <div class="timeline-item">
                    <div class="timeline-content w-full md:w-5/12">
                        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                            <div class="p-6">
                                <h3 class="font-bold text-2xl mb-2">{{ $phase['title'] }}</h3>
                                <div class="flex flex-col gap-2 mb-4">
                                    <div class="flex items-center gap-2 text-sm text-primary">
                                        <i data-lucide="calendar" class="h-4 w-4"></i>
                                        <span>{{ $phase['date'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                        <i data-lucide="map-pin" class="h-4 w-4"></i>
                                        <span>{{ $phase['location'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                        <span>{{ $phase['duration'] }}</span>
                                    </div>
                                </div>
                                <p class="text-muted-foreground mb-4">{{ $phase['desc'] }}</p>
                                <div>
                                    <h4 class="font-bold mb-2 flex items-center gap-2">
                                        <i data-lucide="file-text" class="h-4 w-4 text-primary"></i>
                                        Kegiatan
                                    </h4>
                                    <ul class="list-disc list-inside text-sm text-muted-foreground space-y-1">
                                        @if(is_array($phase->activities))
                                            @foreach ($phase->activities as $activity)
                                            <li>{{ $activity }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-card">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center">HAK & KEWAJIBAN PESERTA</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 text-primary">Hak Peserta</h3>
                    <ul class="space-y-3">
                        @php
                        $hak = [
                            "Mendapatkan informasi yang jelas tentang rangkaian kegiatan ROTASI",
                            "Memperoleh materi dan pengetahuan yang bermanfaat untuk pengembangan diri",
                            "Mendapatkan pendampingan dari panitia dan pembimbing selama kegiatan",
                            "Menggunakan fasilitas yang disediakan selama kegiatan berlangsung",
                            "Menyampaikan pendapat, kritik, dan saran yang konstruktif",
                            "Mendapatkan perlakuan yang adil dan setara dari panitia",
                            "Memperoleh sertifikat kelulusan setelah menyelesaikan seluruh tahapan",
                        ];
                        @endphp
                        @foreach ($hak as $item)
                        <li class="flex items-start gap-2">
                            <span class="text-primary font-bold">✓</span>
                            <span class="text-muted-foreground">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="text-2xl font-bold mb-4 text-primary">Kewajiban Peserta</h3>
                    <ul class="space-y-3">
                        @php
                        $kewajiban = [
                            "Mengikuti seluruh rangkaian kegiatan ROTASI sesuai jadwal",
                            "Mematuhi peraturan dan tata tertib yang berlaku selama kegiatan",
                            "Menyelesaikan tugas dan tanggung jawab yang diberikan",
                            "Berpartisipasi aktif dalam setiap kegiatan dan diskusi",
                            "Menjaga ketertiban, kebersihan, dan kenyamanan lingkungan",
                            "Menghormati panitia, pembimbing, dan sesama peserta",
                            "Menjunjung tinggi nilai-nilai PSTI dalam setiap tindakan",
                        ];
                        @endphp
                        @foreach ($kewajiban as $item)
                        <li class="flex items-start gap-2">
                            <span class="text-primary font-bold">•</span>
                            <span class="text-muted-foreground">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
