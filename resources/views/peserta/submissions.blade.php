@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
        <div class="p-6">
            <div class="flex items-center gap-2 text-lg font-semibold mb-1">
                <i data-lucide="upload" class="h-5 w-5"></i>
                Submission Tugas Saya
            </div>
            <p class="text-sm text-muted-foreground mb-6">Riwayat pengumpulan tugas</p>
            
            <div class="space-y-4">
                @forelse($submissions as $sub)
                    <div class="border rounded-lg p-4 bg-background">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                            <div>
                                <h3 class="font-semibold">{{ $sub->task->title }}</h3>
                                <p class="text-sm text-muted-foreground">Disubmit pada: {{ $sub->submitted_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($sub->submitted_at > $sub->task->due_date)
                                    <span class="inline-flex items-center rounded-full border border-red-200 bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">
                                        Terlambat
                                    </span>
                                @endif
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $sub->status === 'evaluated' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </div>
                        </div>
                        
                        @if($sub->submission_text)
                            <div class="bg-muted p-3 rounded mb-3 text-sm">
                                {{ $sub->submission_text }}
                            </div>
                        @endif
                        
                        @if($sub->file_url)
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-3">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <i data-lucide="file-archive" class="h-4 w-4 flex-shrink-0 text-muted-foreground"></i>
                                    <span class="text-sm truncate">{{ $sub->file_name }}</span>
                                </div>
                                <a href="{{ $sub->file_url }}" download="{{ $sub->file_name }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4">
                                    <i data-lucide="download" class="mr-2 h-4 w-4"></i> Download
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-muted-foreground">
                        Anda belum mengumpulkan tugas apa pun.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
