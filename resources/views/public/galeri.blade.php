@extends('layouts.public')

@section('content')
<section class="pt-32 pb-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center">GALERI & DOKUMENTASI</h1>
            <div class="w-24 h-1 bg-primary mx-auto mb-8"></div>

            <p class="text-lg text-center mb-8">
                Yuk intip keseruan ROTASI dari tahun ke tahun, plus baca langsung cerita seru dari para Savior yang udah ngerasain sendiri pengalaman ini!
            </p>
        </div>
    </div>
</section>

<section class="py-16" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            @php
            $years = $galleries->pluck('year')->unique()->sortDesc()->values();
            $firstYear = $years->first() ?? '2024';
            @endphp
            
            <div x-data="{ activeTab: '{{ $firstYear }}' }" class="w-full">
                <!-- Tabs List -->
                <div class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground mb-8 w-full"
                     style="display: grid; grid-template-columns: repeat({{ count($years) > 0 ? count($years) : 1 }}, minmax(0, 1fr));">
                    @foreach($years as $year)
                    <button @click="activeTab = '{{ $year }}'" 
                            :class="activeTab === '{{ $year }}' ? 'bg-background text-foreground shadow-sm' : ''" 
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium transition-all">
                        ROTASI {{ $year }}
                    </button>
                    @endforeach
                </div>

                <!-- Dynamic Content Loops -->
                @foreach($years as $year)
                <div x-show="activeTab === '{{ $year }}'" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($galleries->where('year', $year) as $image)
                        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow overflow-hidden hover:border-primary/50 transition-colors">
                            <div class="relative h-48 sm:h-56 w-full">
                                <img src="{{ $image->image }}" alt="{{ $image->alt }}" class="object-cover w-full h-full" />
                            </div>
                            <div class="p-3">
                                <p class="text-sm text-center text-muted-foreground">{{ $image->caption }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-card" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-center">TESTIMONI SAVIOR</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-12"></div>

            <div class="grid md:grid-cols-2 gap-8">
                @foreach ($testimonials as $testimoni)
                <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-primary">
                                <img src="{{ $testimoni->image }}" alt="{{ $testimoni->name }}" class="object-cover w-full h-full" />
                            </div>
                            <div>
                                <h3 class="font-bold">{{ $testimoni->name }}</h3>
                                <p class="text-sm text-primary">Angkatan {{ $testimoni->angkatan }}</p>
                            </div>
                        </div>
                        <p class="text-muted-foreground italic">"{{ $testimoni->text }}"</p>
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
            <h2 class="text-3xl font-bold mb-6 text-center">VIDEO HIGHLIGHT</h2>
            <div class="w-24 h-1 bg-primary mx-auto mb-12"></div>

            <div class="aspect-video bg-card border border-border/50 rounded-lg overflow-hidden">
                <iframe
                    width="100%"
                    height="100%"
                    src="https://www.youtube.com/embed/IMrdNpX2PT8?si=Dq8wS8e3nozrPV0V"
                    title="TEASER ROTASI 2025"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    class="w-full h-full"
                ></iframe>
            </div>

            <div class="mt-8 text-center">
                <p class="text-muted-foreground">
                    Kunjungi juga channel YouTube kami untuk melihat lebih banyak video dokumentasi kegiatan ROTASI.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
