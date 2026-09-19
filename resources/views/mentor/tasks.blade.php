@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
        <div class="p-6">
            <div class="flex items-center gap-2 text-lg font-semibold mb-1">
                <i data-lucide="file-text" class="h-5 w-5"></i>
                Tugas Sektor {{ auth()->user()->sektor }}
            </div>
            <p class="text-sm text-muted-foreground mb-6">Daftar tugas yang dibuat oleh divisi acara</p>
            
            <div class="space-y-4">
                @forelse($tasks as $task)
                    <div class="border rounded-lg p-4 bg-background">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <i data-lucide="file-text" class="h-4 w-4 text-muted-foreground"></i>
                                <h3 class="font-semibold">{{ $task->title }}</h3>
                            </div>
                            <div class="flex gap-2">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-secondary text-secondary-foreground">{{ $task->sector == 0 ? 'Semua Sektor' : 'Sektor ' . $task->sector }}</span>
                                @if($task->task_type === 'individu')
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800">Individu</span>
                                @elseif($task->task_type === 'per_sektor')
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-800">Sektor</span>
                                @else
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">Angkatan</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4" x-data="{ expanded: false, isLong: false }" x-init="$nextTick(() => { isLong = $refs.desc.scrollHeight > $refs.desc.clientHeight })">
                            <div x-ref="desc" 
                                    class="text-sm text-muted-foreground whitespace-pre-wrap" 
                                    :class="expanded ? '' : 'overflow-hidden'"
                                    :style="expanded ? '' : 'display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;'">{{ $task->description }}</div>
                            
                            <button x-show="isLong" 
                                    style="display: none;"
                                    @click="expanded = !expanded" 
                                    class="text-xs font-semibold text-primary hover:text-primary/80 transition-colors mt-2 focus:outline-none flex items-center gap-1">
                                <span x-text="expanded ? 'Lihat lebih sedikit' : 'Lihat selengkapnya'"></span>
                                <i data-lucide="chevron-down" class="h-3 w-3 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                            </button>
                        </div>

                        @if($task->attachment_type)
                            <div class="mb-4">
                                <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Lampiran Tugas</h4>
                                @if($task->attachment_type === 'link')
                                    <a href="{{ $task->attachment_url }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                        <i data-lucide="external-link" class="mr-2 h-4 w-4"></i> Buka Tautan
                                    </a>
                                @elseif($task->attachment_type === 'file')
                                    @php
                                        $filename = basename($task->attachment_url);
                                        $originalFilename = str_contains($filename, '_') ? substr($filename, strpos($filename, '_') + 1) : $filename;
                                    @endphp
                                    <a href="{{ asset('storage/' . $task->attachment_url) }}" download="{{ $originalFilename }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                        <i data-lucide="download" class="mr-2 h-4 w-4"></i> Unduh File
                                    </a>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t pt-4">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <i data-lucide="calendar" class="h-4 w-4"></i>
                                Deadline: {{ $task->due_date->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-muted-foreground">
                        Belum ada tugas untuk sektor ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
