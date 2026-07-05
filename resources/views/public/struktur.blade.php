@extends('layouts.public')

@section('content')
<section class="pt-32 pb-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center">STRUKTUR PANITIA</h1>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <p class="text-lg text-center mb-8">
                Kenali tim yang akan membimbing dan mendampingi Anda selama kegiatan ROTASI.
            </p>
        </div>
    </div>
</section>

<section class="py-16" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center">STAKEHOLDERS</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-12"></div>

            <div class="flex justify-center">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 justify-center">
                    @foreach($stakeholders as $stakeholder)
                    <div class="flex flex-col items-center">
                        <div class="relative w-48 h-48 rounded-full overflow-hidden mb-4 border-4 border-primary">
                            <img src="{{ $stakeholder->image }}" alt="{{ $stakeholder->name }}" class="object-cover w-full h-full" />
                        </div>
                        <h3 class="text-xl font-bold text-center">{{ $stakeholder->name }}</h3>
                        <p class="text-primary font-medium mb-3 text-center">{{ $stakeholder->role }}</p>
                        
                        <div class="flex gap-4">
                            @if($stakeholder->instagram)
                            <a href="{{ $stakeholder->instagram }}" target="_blank" rel="noopener noreferrer" class="text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="instagram" class="h-5 w-5"></i>
                            </a>
                            @endif
                            @if($stakeholder->email)
                            <a href="mailto:{{ $stakeholder->email }}" class="text-muted-foreground hover:text-primary transition-colors">
                                <i data-lucide="mail" class="h-5 w-5"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center">DIVISI</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-12"></div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center">
                @foreach ($divisions as $divisi)
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow w-full max-w-md" x-data="{ open: false }">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3">{{ $divisi['name'] }}</h3>
                        <p class="text-sm text-muted-foreground mb-4">{{ $divisi['desc'] }}</p>
                        <div class="shrink-0 bg-border h-[1px] w-full my-4"></div>
                        @php
                            $coreMembers = $divisi->members->filter(function($m) {
                                $role = strtolower($m->role);
                                return !str_contains($role, 'staff') && !str_contains($role, 'staf') && !str_contains($role, 'anggota');
                            });
                            $staffMembers = $divisi->members->filter(function($m) {
                                $role = strtolower($m->role);
                                return str_contains($role, 'staff') || str_contains($role, 'staf') || str_contains($role, 'anggota');
                            });
                        @endphp
                        <ul class="space-y-3 mb-4">
                            @foreach ($coreMembers as $member)
                            <li class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
                                <span class="font-medium">{{ $member->name }}</span>
                                <span class="text-xs px-2.5 py-0.5 bg-secondary text-secondary-foreground rounded-full w-fit">{{ $member->role }}</span>
                            </li>
                            @endforeach
                        </ul>

                        @if($staffMembers->count() > 0)
                        <!-- Accordion for Staff -->
                        <div class="border-t pt-2 mt-4">
                            <button @click="open = !open" class="flex w-full items-center justify-between py-2 text-sm font-medium transition-all hover:text-primary">
                                Daftar Staff / Anggota
                                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-collapse style="display: none;" class="pt-2">
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    @foreach ($staffMembers as $staff)
                                    <li class="flex justify-between items-center bg-muted/50 px-3 py-2 rounded">
                                        <span class="font-medium text-foreground">{{ $staff->name }}</span>
                                        <span class="text-xs">{{ $staff->role }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="py-16" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center">HIMA PSTI UPI</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <div class="flex flex-col md:flex-row gap-8 items-center">
                <div class="md:w-1/3 flex justify-center">
                    <img src="/HIMA-PSTI.svg" alt="HIMA PSTI Logo" class="h-48 w-auto" />
                </div>
                <div class="md:w-2/3">
                    <p class="text-lg mb-4">
                        Himpunan Mahasiswa Pendidikan Sistem dan Teknologi Informasi (HIMA PSTI) UPI adalah organisasi
                        mahasiswa yang mewadahi aspirasi dan kegiatan mahasiswa PSTI UPI.
                    </p>
                    <p class="text-muted-foreground mb-4">
                        HIMA PSTI UPI berperan sebagai pengampu kegiatan ROTASI dan bertanggung jawab atas keberlangsungan
                        program kaderisasi ini dari tahun ke tahun. Melalui ROTASI, HIMA PSTI UPI berupaya untuk membentuk
                        karakter mahasiswa PSTI yang sesuai dengan nilai-nilai dan visi misi program studi.
                    </p>
                    <p class="text-muted-foreground">
                        Seluruh rangkaian kegiatan ROTASI berada di bawah pengawasan dan bimbingan HIMA PSTI UPI, dengan
                        dukungan dari dosen dan pimpinan program studi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
