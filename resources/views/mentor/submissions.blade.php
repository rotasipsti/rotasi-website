@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Submission Sektor {{ auth()->user()->sektor }}</h1>
        <p class="text-muted-foreground">Daftar tugas yang telah dikumpulkan oleh mentee Anda</p>
    </div>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
        <div class="p-6">
            <div class="space-y-4">
                @forelse($submissions as $sub)
                    <div class="border rounded-lg p-4 bg-background">
                        <div class="flex flex-col md:flex-row justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm text-muted-foreground">{{ $sub->submitted_at->format('d M Y, H:i') }}</span>
                                    @if($sub->submitted_at > $sub->task->due_date)
                                        <span class="inline-flex items-center rounded-full border border-red-200 bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">Terlambat</span>
                                    @endif
                                </div>
                                
                                <h3 class="font-semibold text-lg">{{ $sub->task->title }}</h3>
                                <p class="text-sm font-medium mb-2">Oleh: {{ $sub->participant->name }} (NIM: {{ $sub->participant->nim }})</p>
                                
                                @if($sub->submission_text)
                                    <div class="bg-muted p-3 rounded text-sm mb-3">
                                        {{ $sub->submission_text }}
                                    </div>
                                @endif
                                

                            </div>
                            <div class="flex flex-col justify-center gap-2 mt-4 md:mt-0 min-w-[140px] shrink-0">
                                @if($sub->file_url)
                                    <a href="{{ $sub->file_url }}" target="_blank" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-10 px-4">
                                        <i data-lucide="eye" class="mr-2 h-4 w-4"></i> 
                                        Lihat Tugas
                                    </a>
                                    <a href="{{ $sub->file_url }}" download class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4">
                                        <i data-lucide="download" class="mr-2 h-4 w-4"></i> 
                                        Download Tugas
                                    </a>
                                @else
                                    <span class="text-sm text-muted-foreground text-center italic w-full">Tidak ada lampiran</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-muted-foreground">
                        Belum ada tugas yang dikumpulkan oleh peserta.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
