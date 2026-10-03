@php
    $userRole = auth()->user()->role ?? null;
    $runningText = null;
    $popup = null;
    
    if ($userRole && $userRole !== 'admin') {
        $targetRoles = ['semua', $userRole];
        if ($userRole !== 'peserta') {
            $targetRoles[] = 'seluruh_panitia';
        }
        
        $runningText = \App\Models\Announcement::where('is_active', true)
            ->whereIn('target_role', $targetRoles)
            ->where('type', 'running_text')
            ->latest()
            ->first();
            
        $popup = \App\Models\Announcement::where('is_active', true)
            ->whereIn('target_role', $targetRoles)
            ->where('type', 'popup')
            ->latest()
            ->first();
    }
@endphp

@if($runningText)
    <div class="bg-primary text-primary-foreground py-2 px-4 overflow-hidden fixed top-0 left-0 w-full z-[60] h-[40px] flex items-center">
        <div class="whitespace-nowrap animate-marquee inline-block">
            <span class="font-bold mr-2">{{ $runningText->title }}:</span>
            <span>{{ $runningText->message }}</span>
            @if($runningText->link)
                <a href="{{ $runningText->link }}" class="underline ml-4 text-primary-foreground/80 hover:text-primary-foreground" target="_blank">{{ $runningText->link_text ?: 'Tautan' }}</a>
            @endif
        </div>
    </div>
    <style>
        /* Push layout down to accommodate the 40px running text */
        body {
            padding-top: 40px !important;
        }
        header.fixed.top-0 {
            top: 40px !important;
        }
        aside.fixed.top-16 {
            top: calc(4rem + 40px) !important;
        }
        
        .animate-marquee {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }
    </style>
@endif

@if($popup)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('announcement_{{ $popup->id }}')) {
                Swal.fire({
                    title: '<h2 class="text-xl font-bold font-inter mt-2">{{ $popup->title }}</h2>',
                    html: '<p class="text-sm mt-1">{{ $popup->message }}</p>',
                    icon: 'info',
                    showCancelButton: {{ $popup->link ? 'true' : 'false' }},
                    confirmButtonText: 'OK',
                    cancelButtonText: '{{ $popup->link_text ?: 'Tautan' }}',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                        title: 'text-foreground',
                        htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                        confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none',
                        cancelButton: 'bg-secondary text-secondary-foreground hover:bg-secondary/80 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none ml-3',
                        actions: '!mt-6',
                        icon: '!border-blue-500 !text-blue-500 !m-0 !mx-auto'
                    },
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                }).then((result) => {
                    sessionStorage.setItem('announcement_{{ $popup->id }}', 'seen');
                    if (result.dismiss === Swal.DismissReason.cancel && '{{ $popup->link }}') {
                        window.open('{{ $popup->link }}', '_blank');
                    }
                });
            }
        });
    </script>
@endif
