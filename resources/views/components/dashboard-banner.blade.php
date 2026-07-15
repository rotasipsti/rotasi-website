@props(['role' => 'all'])

@php
    // Fetch banners based on role
    // Role mapping to target_role
    $targetRoles = ['all_except_admin']; // Everyone gets this (except admin, but admin won't render this component)
    
    if ($role === 'peserta') {
        $targetRoles[] = 'peserta';
    } else {
        // Panitia of some sort
        $targetRoles[] = 'semua_panitia';
        if ($role === 'mentor') $targetRoles[] = 'mentor';
        if ($role === 'acara') $targetRoles[] = 'acara';
        if ($role === 'keamanan') $targetRoles[] = 'keamanan';
        if ($role === 'panitia') $targetRoles[] = 'panitia';
    }

    $banners = \App\Models\Banner::where('is_active', true)
        ->whereIn('target_role', $targetRoles)
        ->oldest()
        ->get();
@endphp

@if($banners->count() > 0)
    <div class="mb-8 w-full max-w-full">
        <div x-data="{
                activeSlide: 0,
                slides: {{ $banners->count() }},
                next() {
                    this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1;
                },
                prev() {
                    this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1;
                },
                init() {
                    if(this.slides > 1) {
                        setInterval(() => { this.next() }, 5000);
                    }
                }
            }" 
            class="relative w-full rounded-xl overflow-hidden group" style="aspect-ratio: 3/1; max-height: 280px;">
            
            <!-- Slides -->
            <div class="w-full h-full relative">
                @foreach($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-x-full"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-500 absolute inset-0"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-full"
                         class="w-full h-full absolute top-0 left-0">
                        @if($banner->action_url)
                            <a href="{{ $banner->action_url }}" target="_blank" class="block w-full h-full cursor-pointer hover:opacity-95 transition-opacity">
                        @endif
                        
                        @if($banner->type === 'upload' && $banner->image_path)
                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        @elseif($banner->type === 'link' && $banner->image_url)
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        @endif
                        
                        @if($banner->action_url)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Arrows -->
            @if($banners->count() > 1)
                <button @click="prev()" class="absolute left-4 top-1/2 -mt-5 hover:-translate-y-0 w-10 h-10 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10" aria-label="Previous banner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button @click="next()" class="absolute right-4 top-1/2 -mt-5 hover:-translate-y-0 w-10 h-10 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10" aria-label="Next banner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>

                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                    @foreach($banners as $index => $banner)
                        <button @click="activeSlide = {{ $index }}" 
                                :class="{'bg-white w-6': activeSlide === {{ $index }}, 'bg-white/50 w-2': activeSlide !== {{ $index }}}"
                                class="h-2 rounded-full transition-all duration-300" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
