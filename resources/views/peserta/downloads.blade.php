@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
        <div class="p-6">
            <div class="flex items-center gap-2 text-lg font-semibold mb-1">
                <i data-lucide="download" class="h-5 w-5"></i>
                Dokumen & Berkas
            </div>
            <p class="text-sm text-muted-foreground mb-6">Unduh berbagai dokumen resmi, panduan, dan berkas penting ROTASI</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($downloads as $file)
                <div class="border rounded-lg p-4 bg-background flex flex-col h-full">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-primary mb-2">
                            <i data-lucide="{{ $file->icon ?? 'file-text' }}" class="h-5 w-5"></i>
                            <h3 class="font-semibold text-foreground">{{ $file->title }}</h3>
                        </div>
                        <p class="text-sm text-muted-foreground mb-4">{{ $file->desc }}</p>
                    </div>
                    <a href="{{ $file->url }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors bg-secondary text-secondary-foreground hover:bg-secondary/80 h-9 px-4 w-full">
                        <i data-lucide="external-link" class="mr-2 h-4 w-4"></i> Buka Berkas
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center py-8 text-muted-foreground">
                    Belum ada dokumen yang tersedia untuk diunduh.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
