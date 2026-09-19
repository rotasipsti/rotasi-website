@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div x-data="taskUpload">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="p-6">
                <div class="flex items-center gap-2 text-lg font-semibold mb-1">
                    <i data-lucide="file-text" class="h-5 w-5"></i>
                    Tugas Sektor {{ auth()->user()->sektor }}
                </div>
                <p class="text-sm text-muted-foreground mb-6">Daftar tugas yang harus dikumpulkan</p>
                
                <div class="space-y-4">
                    @forelse($tasks as $task)
                        @php
                            $submission = $submissions->where('task_id', $task->id)->first();
                            $status = $submission ? $submission->status : 'not_submitted';
                            $isLate = false;
                            
                            if ($submission) {
                                $isLate = $submission->submitted_at > $task->due_date;
                            } else {
                                $isLate = now() > $task->due_date;
                            }
                        @endphp
                        
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
                                    
                                    @if($isLate)
                                        <span class="ml-2 inline-flex items-center rounded-full border border-red-200 bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">Terlambat</span>
                                    @endif
                                </div>
                                
                                <div class="w-full sm:w-auto">
                                    @if($status === 'not_submitted')
                                        <button @click="uploadDialog = true; selectedTask = {{ $task->id }}" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-3">
                                            <i data-lucide="upload" class="mr-2 h-4 w-4"></i> Upload Tugas
                                        </button>
                                    @else
                                        <button @click="uploadDialog = true; selectedTask = {{ $task->id }}" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                            <i data-lucide="edit" class="mr-2 h-4 w-4"></i> Edit Tugas
                                        </button>
                                    @endif
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

        <!-- Upload Modal -->
        <div x-show="uploadDialog" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div @click.away="uploadDialog = false" class="bg-card w-full max-w-md rounded-xl shadow-lg border border-border/50 p-6 relative">
                <button @click="uploadDialog = false" class="absolute right-4 top-4 text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
                
                <h2 class="text-lg font-semibold mb-1">Kirim / Edit Tugas</h2>
                <p class="text-sm text-muted-foreground mb-4">Pilih atau tarik file yang ingin diupload.</p>
                
                <div x-show="errorMessage" style="display: none;" class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-2">
                    <i data-lucide="alert-circle" class="h-4 w-4 mt-0.5 shrink-0"></i>
                    <span x-text="errorMessage"></span>
                </div>
                
                <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                    @csrf
                    <input type="hidden" name="task_id" :value="selectedTask">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium">Deskripsi Tugas (Opsional)</label>
                            <textarea name="submission_text" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1 min-h-24"></textarea>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium">Upload File (Maks 50MB)</label>
                            <div class="mt-2 w-full">
                                <label for="file-upload" 
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer transition-colors"
                                    :class="isDragging ? 'border-primary bg-primary/5' : 'border-border/50 bg-muted/30 hover:bg-muted/50'"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="isDragging = false; const files = $event.dataTransfer.files; if(files.length > 0) { $refs.fileInput.files = files; fileName = files[0].name; }">
                                    
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4" x-show="!isUploading">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 mb-3 transition-colors" :class="isDragging ? 'text-primary' : 'text-muted-foreground'"></i>
                                        <p class="mb-1 text-sm text-muted-foreground" x-show="!fileName"><span class="font-semibold">Klik untuk upload</span> atau tarik file ke sini</p>
                                        <p class="text-xs text-muted-foreground" x-show="!fileName">Semua jenis file didukung</p>
                                        <p class="text-sm font-medium text-primary break-all" x-show="fileName" x-text="fileName"></p>
                                    </div>
                                    
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 w-full h-full" x-show="isUploading" style="display: none;">
                                        <div class="w-full max-w-[200px] sm:max-w-[250px] space-y-3 flex flex-col items-center">
                                            <!-- Percentage Text -->
                                            <div class="text-2xl sm:text-3xl font-bold tracking-widest text-foreground" x-text="uploadProgress + '%'"></div>
                                            
                                            <!-- Progress Bar Container (Pill shape with thick border) -->
                                            <div class="w-full h-5 sm:h-6 border-[3px] border-foreground rounded-full p-[2px] bg-transparent flex items-center">
                                                <!-- Progress Fill -->
                                                <div class="h-full bg-foreground rounded-full transition-all duration-300 ease-out" :style="`width: ${uploadProgress}%`"></div>
                                            </div>
                                            
                                            <!-- File Name Display -->
                                            <p class="text-[10px] sm:text-xs text-muted-foreground truncate w-full" x-text="fileName"></p>
                                        </div>
                                    </div>
                                    <input id="file-upload" x-ref="fileInput" name="file" type="file" class="hidden" @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" />
                                </label>
                            </div>
                        </div>
                        
                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="uploadDialog = false; fileName = ''; $refs.fileInput.value = ''" :disabled="isUploading" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-4 disabled:opacity-50">
                                Batal
                            </button>
                            <button type="submit" :disabled="isUploading" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 disabled:opacity-50 min-w-[100px]">
                                <span x-show="!isUploading">Upload</span>
                                <span x-show="isUploading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('taskUpload', () => ({
        uploadDialog: false,
        selectedTask: null,
        isUploading: false,
        isDragging: false,
        fileName: '',
        uploadProgress: 0,
        errorMessage: '',
        
        submitForm(e) {
            this.isUploading = true;
            this.errorMessage = '';
            this.uploadProgress = 0;
            
            let formData = new FormData(e.target);
            let xhr = new XMLHttpRequest();
            xhr.open('POST', e.target.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.upload.addEventListener('progress', (event) => {
                if (event.lengthComputable) {
                    this.uploadProgress = Math.round((event.loaded / event.total) * 100);
                }
            });
            
            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    this.uploadProgress = 100;
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    this.isUploading = false;
                    this.uploadProgress = 0;
                    try {
                        let res = JSON.parse(xhr.responseText);
                        this.errorMessage = res.message || 'Terjadi kesalahan saat mengupload.';
                    } catch(err) {
                        this.errorMessage = 'Terjadi kesalahan pada server.';
                    }
                }
            };
            
            xhr.onerror = () => {
                this.isUploading = false;
                this.uploadProgress = 0;
                this.errorMessage = 'Koneksi terputus. Silakan coba lagi.';
            };
            
            xhr.send(formData);
        }
    }));
});
</script>
@endsection
