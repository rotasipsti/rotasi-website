@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    modalOpen: false, 
    isLoading: false, 
    taskTitle: '', 
    submitted: [], 
    notSubmitted: [],
    
    cekPengumpulan(id, title) {
        this.modalOpen = true;
        this.isLoading = true;
        this.taskTitle = title;
        this.submitted = [];
        this.notSubmitted = [];
        
        fetch(`/accounts/stakeholder/tasks/${id}/status`)
            .then(res => res.json())
            .then(data => {
                this.submitted = data.submitted;
                this.notSubmitted = data.not_submitted;
                this.isLoading = false;
            })
            .catch(err => {
                this.isLoading = false;
                console.error(err);
                alert('Gagal mengambil data pengumpulan');
            });
    }
}">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Data Tugas Acara</h1>
        <p class="text-muted-foreground">Detail seluruh tugas yang dibuat oleh divisi acara beserta progres pengumpulan pesertanya.</p>
    </div>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
        <div class="p-6">
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
                            <button @click="cekPengumpulan({{ $task->id }}, '{{ addslashes($task->title) }}')" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 shrink-0">
                                <i data-lucide="users" class="mr-2 h-4 w-4"></i> Cek Pengumpulan
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-muted-foreground">
                        Belum ada tugas yang dibuat oleh Divisi Acara.
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Status Pengumpulan -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.away="modalOpen = false" class="bg-card w-full max-w-2xl rounded-xl shadow-lg border border-border/50 p-6 relative max-h-[90vh] flex flex-col">
            <button @click="modalOpen = false" class="absolute right-4 top-4 text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
            
            <div class="mb-4 pr-6">
                <h2 class="text-xl font-bold">Status Pengumpulan Tugas</h2>
                <p class="text-sm text-muted-foreground" x-text="taskTitle"></p>
            </div>
            
            <div x-show="isLoading" class="flex-1 flex flex-col items-center justify-center py-12">
                <svg class="animate-spin h-8 w-8 text-primary mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-muted-foreground">Memuat data peserta...</p>
            </div>
            
            <div x-show="!isLoading" style="display: none;" class="flex-1 overflow-y-auto min-h-0 space-y-6">
                <!-- Sudah Mengumpulkan -->
                <div>
                    <h3 class="text-sm font-semibold flex items-center gap-2 mb-3 text-green-600">
                        <i data-lucide="check-circle" class="h-4 w-4"></i> Sudah Mengumpulkan (<span x-text="submitted.length"></span>)
                    </h3>
                    <div class="border rounded-md divide-y overflow-hidden">
                        <template x-if="submitted.length === 0">
                            <div class="p-3 text-sm text-muted-foreground text-center bg-muted/20">Belum ada peserta yang mengumpulkan.</div>
                        </template>
                        <template x-for="p in submitted" :key="p.id">
                            <div class="p-3 flex flex-col sm:flex-row sm:items-center justify-between hover:bg-muted/30 transition-colors gap-2">
                                <div>
                                    <p class="text-sm font-medium" x-text="p.name"></p>
                                    <p class="text-xs text-muted-foreground" x-text="p.nim"></p>
                                </div>
                                <div class="text-xs font-semibold px-2 py-1 bg-secondary text-secondary-foreground rounded self-start sm:self-auto" x-text="'Sektor ' + (p.sektor || '-')"></div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Belum Mengumpulkan -->
                <div>
                    <h3 class="text-sm font-semibold flex items-center gap-2 mb-3 text-destructive">
                        <i data-lucide="x-circle" class="h-4 w-4"></i> Belum Mengumpulkan (<span x-text="notSubmitted.length"></span>)
                    </h3>
                    <div class="border rounded-md divide-y overflow-hidden">
                        <template x-if="notSubmitted.length === 0">
                            <div class="p-3 text-sm text-muted-foreground text-center bg-muted/20">Semua peserta telah mengumpulkan.</div>
                        </template>
                        <template x-for="p in notSubmitted" :key="p.id">
                            <div class="p-3 flex flex-col sm:flex-row sm:items-center justify-between hover:bg-muted/30 transition-colors gap-2">
                                <div>
                                    <p class="text-sm font-medium" x-text="p.name"></p>
                                    <p class="text-xs text-muted-foreground" x-text="p.nim"></p>
                                </div>
                                <div class="text-xs font-semibold px-2 py-1 bg-secondary text-secondary-foreground rounded self-start sm:self-auto" x-text="'Sektor ' + (p.sektor || '-')"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
