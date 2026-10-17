@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div x-data="taskUpload">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="p-6">
                @php
                    $totalTasks = $tasks->count();
                    $submittedCount = $tasks->filter(function($task) use ($submissions) {
                        return $submissions->contains('task_id', $task->id);
                    })->count();
                    $percentage = $totalTasks > 0 ? round(($submittedCount / $totalTasks) * 100) : 0;
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <div class="flex items-center gap-2.5 text-lg font-semibold mb-1">
                            <i data-lucide="file-text" class="h-5 w-5 text-foreground"></i>
                            <span>Tugas Sektor {{ auth()->user()->sektor }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $submittedCount === $totalTasks && $totalTasks > 0 ? 'bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-400' : 'bg-primary/10 text-primary' }}">
                                {{ $percentage }}%
                            </span>
                        </div>
                        <p class="text-sm text-muted-foreground">Daftar tugas yang harus dikumpulkan</p>
                    </div>

                    <!-- Progress Bar Total Pengumpulan Tugas -->
                    <div class="w-full sm:w-72 md:w-80 bg-muted/40 dark:bg-muted/20 border border-border/60 rounded-xl p-3 sm:p-3.5 shadow-xs shrink-0">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-medium text-muted-foreground flex items-center gap-1.5">
                                <i data-lucide="{{ $submittedCount === $totalTasks && $totalTasks > 0 ? 'check-circle-2' : 'upload' }}" class="h-3.5 w-3.5 {{ $submittedCount === $totalTasks && $totalTasks > 0 ? 'text-green-600 dark:text-green-500' : 'text-primary' }}"></i>
                                Progres Pengumpulan
                            </span>
                            <span class="font-bold text-foreground">
                                {{ $submittedCount }}/{{ $totalTasks }}
                                <span class="text-muted-foreground font-normal">({{ $percentage }}%)</span>
                            </span>
                        </div>
                        
                        <!-- Progress Bar Track -->
                        <div class="w-full bg-secondary/80 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 ease-out {{ $submittedCount === $totalTasks && $totalTasks > 0 ? 'bg-green-600 dark:bg-green-500' : 'bg-primary' }}"
                                 style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="mt-1.5 flex items-center justify-between text-[11px] text-muted-foreground">
                            @if($totalTasks == 0)
                                <span>Belum ada tugas</span>
                            @elseif($submittedCount === $totalTasks)
                                <span class="text-green-600 dark:text-green-500 font-medium flex items-center gap-1">
                                    <i data-lucide="check" class="h-3 w-3"></i> Semua tugas telah dikumpulkan
                                </span>
                            @else
                                <span>{{ $totalTasks - $submittedCount }} tugas belum dikumpulkan</span>
                            @endif
                        </div>
                    </div>
                </div>
                
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
                                
                                <div class="w-full sm:w-auto flex flex-wrap items-center gap-2">
                                    @if($status === 'not_submitted')
                                        <button @click="openModal({{ $task->id }}, false)" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-3">
                                            <i data-lucide="upload" class="mr-2 h-4 w-4"></i> Upload Tugas
                                        </button>
                                    @else
                                        @php
                                            $hasFile = $submission && !empty($submission->file_url);
                                            $existingText = $submission->submission_text ?? '';
                                            $existingFile = $submission->file_name ?? '';
                                            $isEvaluated = $submission && $submission->status === 'evaluated';
                                        @endphp
                                        <button @click="openModal({{ $task->id }}, true, {{ \Illuminate\Support\Js::from($existingText) }}, {{ \Illuminate\Support\Js::from($existingFile) }}, {{ $hasFile ? 'true' : 'false' }})" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                            <i data-lucide="edit" class="mr-2 h-4 w-4"></i> Edit Tugas
                                        </button>
                                        @if(!$isEvaluated)
                                            <form action="{{ route('submissions.destroy', $submission->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengiriman tugas ini? File dan keterangan yang telah dikirim akan dihapus.')" class="w-full sm:w-auto inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-500/10 h-9 px-3">
                                                    <i data-lucide="rotate-ccw" class="mr-1.5 h-4 w-4"></i> Batalkan Pengumpulan
                                                </button>
                                            </form>
                                        @endif
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

        <!-- Upload / Edit Modal -->
        <div x-show="uploadDialog" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="closeModal()"
             style="display: none;" 
             class="fixed inset-0 z-[70] overflow-y-auto bg-black/60 backdrop-blur-xs p-4 flex items-center justify-center">
            
            <div @click.away="closeModal()" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-card w-full max-w-md rounded-xl shadow-2xl border border-border/50 p-5 sm:p-6 relative my-auto max-h-[calc(100dvh-2rem)] overflow-y-auto">
                <button @click="closeModal()" type="button" class="absolute right-4 top-4 text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
                
                <h2 class="text-lg font-semibold mb-1 pr-6" x-text="isEdit ? 'Edit Tugas' : 'Upload Tugas'">Kirim / Edit Tugas</h2>
                <p class="text-sm text-muted-foreground mb-4" x-text="isEdit ? (hasExistingFile ? 'Perbarui keterangan atau unggah file baru untuk mengganti file sebelumnya.' : 'Tugas belum memiliki file. Silakan lampirkan file tugas.') : 'Pilih atau tarik file tugas yang ingin diupload.'"></p>
                
                <div x-show="errorMessage" style="display: none;" class="mb-4 p-3 rounded-md bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-sm flex items-start gap-2">
                    <i data-lucide="alert-circle" class="h-4 w-4 mt-0.5 shrink-0"></i>
                    <span x-text="errorMessage"></span>
                </div>
                
                <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                    @csrf
                    <input type="hidden" name="task_id" :value="selectedTask">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium">Deskripsi Tugas (Opsional)</label>
                            <textarea name="submission_text" x-model="submissionText" placeholder="Tambahkan catatan atau keterangan tugas (opsional)..." class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1 min-h-24 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"></textarea>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-sm font-medium">
                                    Upload File 
                                    <span x-show="!isEdit || !hasExistingFile" class="text-red-500 font-bold">*</span>
                                    <span class="text-xs text-muted-foreground font-normal">(Maks 50MB)</span>
                                </label>
                                <span x-show="isEdit && hasExistingFile" class="text-xs text-muted-foreground">Opsional</span>
                                <span x-show="!isEdit || !hasExistingFile" class="text-xs text-red-500 font-medium">Wajib</span>
                            </div>

                            <!-- File yang sudah tersimpan saat ini jika mode edit -->
                            <div x-show="isEdit && hasExistingFile && existingFileName" class="mb-2 p-2.5 rounded-lg bg-muted/40 border border-border/50 text-xs flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i data-lucide="paperclip" class="w-4 h-4 text-primary shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="text-muted-foreground text-[11px]">File saat ini:</p>
                                        <p class="font-medium text-foreground truncate" x-text="existingFileName"></p>
                                    </div>
                                </div>
                                <span class="text-[10px] bg-secondary text-secondary-foreground px-2 py-0.5 rounded font-medium shrink-0">Tersimpan</span>
                            </div>
                            
                            <div class="w-full">
                                <label for="file-upload" 
                                    class="flex flex-col items-center justify-center w-full min-h-32 py-4 border-2 border-dashed rounded-lg cursor-pointer transition-colors"
                                    :class="isDragging ? 'border-primary bg-primary/5' : 'border-border/50 bg-muted/30 hover:bg-muted/50'"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="
                                        isDragging = false; 
                                        const files = $event.dataTransfer.files; 
                                        if(files.length > 0) { 
                                            if (files[0].size > 50 * 1024 * 1024) {
                                                errorMessage = 'Ukuran file melebihi batas maksimal 50MB.';
                                                $refs.fileInput.value = '';
                                                fileName = '';
                                            } else {
                                                $refs.fileInput.files = files; 
                                                fileName = files[0].name; 
                                                errorMessage = ''; 
                                            }
                                        }
                                    ">
                                    
                                    <div class="flex flex-col items-center justify-center text-center px-4" x-show="!isUploading">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 mb-2 transition-colors" :class="isDragging ? 'text-primary' : 'text-muted-foreground'"></i>
                                        
                                        <div x-show="!fileName">
                                            <p class="mb-1 text-sm text-muted-foreground">
                                                <span class="font-semibold text-foreground" x-text="(isEdit && hasExistingFile) ? 'Klik untuk ganti file' : 'Klik untuk upload'"></span> atau tarik file ke sini
                                            </p>
                                            <p class="text-xs text-muted-foreground" x-show="isEdit && hasExistingFile">
                                                Biarkan kosong jika tidak ingin mengubah file saat ini
                                            </p>
                                            <p class="text-xs text-muted-foreground" x-show="!isEdit || !hasExistingFile">
                                                <span class="text-red-500 font-medium">Wajib melampirkan file</span> • Semua jenis file didukung
                                            </p>
                                        </div>

                                        <div x-show="fileName" class="space-y-1 w-full max-w-xs">
                                            <p class="text-sm font-semibold text-primary break-all" x-text="fileName"></p>
                                            <p class="text-[11px] text-muted-foreground" x-show="isEdit && hasExistingFile">
                                                File baru ini akan menggantikan file sebelumnya
                                            </p>
                                            <p class="text-[11px] text-muted-foreground" x-show="!isEdit || !hasExistingFile">
                                                File siap diupload (klik lagi untuk mengganti)
                                            </p>
                                            <button type="button" 
                                                x-show="isEdit && hasExistingFile"
                                                @click.stop.prevent="fileName = ''; if($refs.fileInput) $refs.fileInput.value = '';" 
                                                class="mt-1 text-xs text-red-500 hover:underline inline-block">
                                                Batal ganti file
                                            </button>
                                        </div>
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
                                            <p class="text-[10px] sm:text-xs text-muted-foreground truncate w-full" x-text="fileName || existingFileName"></p>
                                        </div>
                                    </div>
                                    <input id="file-upload" x-ref="fileInput" name="file" type="file" class="hidden" 
                                        @change="
                                            if ($event.target.files.length > 0) {
                                                const f = $event.target.files[0];
                                                if (f.size > 50 * 1024 * 1024) {
                                                    errorMessage = 'Ukuran file melebihi batas maksimal 50MB.';
                                                    $refs.fileInput.value = '';
                                                    fileName = '';
                                                } else {
                                                    fileName = f.name;
                                                    errorMessage = '';
                                                }
                                            } else {
                                                fileName = '';
                                            }
                                        " />
                                </label>
                            </div>
                        </div>
                        
                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="closeModal()" :disabled="isUploading" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-4 disabled:opacity-50">
                                Batal
                            </button>
                            <button type="submit" :disabled="isUploading" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 disabled:opacity-50 min-w-[100px]">
                                <span x-show="!isUploading" x-text="isEdit ? 'Simpan' : 'Upload'">Upload</span>
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
        isEdit: false,
        hasExistingFile: false,
        existingFileName: '',
        submissionText: '',
        isUploading: false,
        isDragging: false,
        fileName: '',
        uploadProgress: 0,
        errorMessage: '',
        
        openModal(taskId, isEdit = false, text = '', currentFileName = '', hasFile = false) {
            this.selectedTask = taskId;
            this.isEdit = isEdit;
            this.submissionText = text || '';
            this.existingFileName = currentFileName || '';
            this.hasExistingFile = hasFile;
            this.fileName = '';
            this.errorMessage = '';
            this.uploadProgress = 0;
            this.isUploading = false;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
            this.uploadDialog = true;
            document.body.classList.add('overflow-hidden');
            this.$nextTick(() => {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });
        },
        
        closeModal() {
            if (this.isUploading) return;
            this.uploadDialog = false;
            document.body.classList.remove('overflow-hidden');
            this.fileName = '';
            this.errorMessage = '';
            this.submissionText = '';
            this.selectedTask = null;
            this.isEdit = false;
            this.hasExistingFile = false;
            this.existingFileName = '';
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        submitForm(e) {
            const isFileRequired = !this.isEdit || !this.hasExistingFile;
            const hasSelectedFile = this.$refs.fileInput && this.$refs.fileInput.files.length > 0;
            
            if (isFileRequired && !hasSelectedFile) {
                this.errorMessage = 'File tugas wajib dilampirkan.';
                return;
            }

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
                        if (res.errors && typeof res.errors === 'object') {
                            let firstErr = Object.values(res.errors)[0];
                            this.errorMessage = Array.isArray(firstErr) ? firstErr[0] : (res.message || 'Terjadi kesalahan saat mengupload.');
                        } else {
                            this.errorMessage = res.message || 'Terjadi kesalahan saat mengupload.';
                        }
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
