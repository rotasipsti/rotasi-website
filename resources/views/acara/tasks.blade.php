@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Manajemen Tugas</h1>
            <p class="text-muted-foreground">Buat dan kelola tugas untuk peserta ROTASI</p>
        </div>
    </div>



    <div x-data="{ 
        dialogOpen: false, 
        isEdit: false,
        taskId: '',
        title: '',
        description: '',
        due_date: '',
        attachment_type: 'none',
        attachment_link: '',
        attachment_url: '',
        isDragging: false,
        fileName: '',
        isUploading: false,
        uploadProgress: 0,
        errorMessage: '',
        selectionMode: false,
        selectedTasks: [],
        selectAll: false,
        toggleSelectionMode() {
            this.selectionMode = !this.selectionMode;
            if (!this.selectionMode) {
                this.selectedTasks = [];
                this.selectAll = false;
            }
        },
        toggleAll() {
            if (this.selectAll) {
                this.selectedTasks = [];
            } else {
                this.selectedTasks = {{ json_encode($tasks->pluck('id')) }};
            }
            this.selectAll = !this.selectAll;
        },
        
        openCreate() {
            this.isEdit = false;
            this.taskId = '';
            this.title = '';
            this.description = '';
            this.task_type = 'individu';
            this.sector = '0';
            this.due_date = '';
            this.attachment_type = 'none';
            this.attachment_link = '';
            this.attachment_url = '';
            this.fileName = '';
            this.dialogOpen = true;
        },
        
        openEdit(task) {
            this.isEdit = true;
            this.taskId = task.id;
            this.title = task.title;
            this.description = task.description;
            this.task_type = task.task_type;
            this.sector = task.sector;
            this.due_date = task.due_date_formatted;
            this.attachment_type = task.attachment_type || 'none';
            this.attachment_link = task.attachment_type === 'link' ? task.attachment_url : '';
            this.attachment_url = task.attachment_url || '';
            this.fileName = task.attachment_type === 'file' && task.attachment_url ? task.attachment_url.split('/').pop() : '';
            this.dialogOpen = true;
        },

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
                        this.errorMessage = res.message || 'Terjadi kesalahan saat menyimpan.';
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
    }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-semibold">Daftar Tugas ({{ count($tasks) }})</h2>
                @if(count($tasks) > 0)
                    <div x-show="selectionMode" x-transition style="display: none;" class="flex items-center gap-2 bg-card border border-border/50 px-3 py-1.5 rounded-md shadow-sm">
                        <input type="checkbox" id="selectAll" class="rounded border-input text-primary focus:ring-primary h-4 w-4 cursor-pointer" @click="toggleAll()" :checked="selectAll">
                        <label for="selectAll" class="text-sm font-medium cursor-pointer">Pilih Semua</label>
                    </div>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div x-show="selectionMode" x-transition style="display: none;">
                    <form action="{{ route('tasks.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas yang Anda pilih? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedTasks" :key="id">
                            <input type="hidden" name="task_ids[]" :value="id">
                        </template>
                        <button type="submit" :disabled="selectedTasks.length === 0" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-destructive bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 shadow disabled:opacity-50">
                            <i data-lucide="trash-2" class="mr-2 h-4 w-4"></i> Hapus (<span x-text="selectedTasks.length"></span>)
                        </button>
                    </form>
                </div>
                
                @if(count($tasks) > 0)
                    <button @click="toggleSelectionMode()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent text-foreground h-10 px-4 shadow">
                        <i data-lucide="check-square" class="mr-2 h-4 w-4" x-show="!selectionMode"></i> 
                        <i data-lucide="x" class="mr-2 h-4 w-4" x-show="selectionMode" style="display: none;"></i> 
                        <span x-text="selectionMode ? 'Batal' : 'Hapus Tugas'"></span>
                    </button>
                @endif

                <button x-show="!selectionMode" @click="openCreate()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 shadow">
                    <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i> 
                    Buat Tugas Baru
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($tasks as $task)
                        <div class="border rounded-lg p-4 bg-background">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div x-show="selectionMode" x-transition style="display: none;" class="flex items-center justify-center">
                                        <input type="checkbox" value="{{ $task->id }}" x-model="selectedTasks" @change="selectAll = selectedTasks.length === {{ count($tasks) }}" class="rounded border-input text-primary focus:ring-primary h-4 w-4 cursor-pointer">
                                    </div>
                                    <i data-lucide="file-text" class="h-4 w-4 text-muted-foreground hidden sm:block"></i>
                                    <h3 class="font-semibold">{{ $task->title }}</h3>
                                </div>
                                <div class="flex gap-2">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-secondary text-secondary-foreground">
                                        @if($task->task_type === 'individu') Individu
                                        @elseif($task->task_type === 'per_sektor') {{ $task->sector == 0 ? 'Semua Sektor' : 'Sektor ' . $task->sector }}
                                        @else Angkatan
                                        @endif
                                    </span>
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
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t pt-4">
                                <div class="flex items-center gap-4 text-sm font-medium">
                                    <span class="flex items-center gap-1 text-muted-foreground">
                                        <i data-lucide="calendar" class="h-4 w-4"></i> Deadline: {{ $task->due_date->format('d M Y, H:i') }}
                                    </span>
                                    <span class="flex items-center gap-1 text-primary">
                                        <i data-lucide="upload-cloud" class="h-4 w-4"></i> {{ $task->submissions_count }} Pengumpulan
                                    </span>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-2" x-show="!selectionMode" x-transition>
                                    @php
                                        $taskData = [
                                            'id' => $task->id,
                                            'title' => $task->title,
                                            'description' => $task->description,
                                            'task_type' => $task->task_type,
                                            'sector' => $task->sector,
                                            'due_date_formatted' => $task->due_date->format('Y-m-d\TH:i'),
                                            'attachment_type' => $task->attachment_type,
                                            'attachment_url' => $task->attachment_url
                                        ];
                                    @endphp
                                    <button type="button" @click='openEdit(@json($taskData))' class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent h-9 px-3">
                                        <i data-lucide="edit" class="mr-2 h-4 w-4"></i> Edit
                                    </button>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini? Semua data pengumpulan akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-destructive bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 px-3">
                                            <i data-lucide="trash-2" class="mr-2 h-4 w-4"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-muted-foreground">
                            Belum ada tugas yang dibuat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $tasks->links() }}
        </div>

        <!-- Create/Edit Task Modal -->
        <div x-show="dialogOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div @click.away="dialogOpen = false" class="bg-card w-full max-w-lg rounded-xl shadow-lg border border-border/50 p-6 relative max-h-[90vh] overflow-y-auto">
                <button @click="dialogOpen = false" class="absolute right-4 top-4 text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
                
                <h2 class="text-xl font-bold mb-1" x-text="isEdit ? 'Edit Tugas' : 'Buat Tugas Baru'"></h2>
                <p class="text-sm text-muted-foreground mb-6" x-text="isEdit ? 'Perbarui informasi tugas yang sudah ada.' : 'Tambahkan tugas baru untuk peserta ROTASI.'"></p>
                
                <div x-show="errorMessage" style="display: none;" class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-2">
                    <i data-lucide="alert-circle" class="h-4 w-4 mt-0.5 shrink-0"></i>
                    <span x-text="errorMessage"></span>
                </div>

                <form :action="isEdit ? '{{ url('tasks') }}/' + taskId : '{{ route('tasks.store') }}'" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium">Judul Tugas</label>
                            <input type="text" name="title" x-model="title" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1">
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium">Tipe Tugas</label>
                            <select name="task_type" x-model="task_type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1">
                                <option value="individu">Individu</option>
                                <option value="per_sektor">Per Sektor</option>
                                <option value="angkatan">Satu Angkatan</option>
                            </select>
                        </div>
                        
                        <div x-show="task_type === 'per_sektor'">
                            <label class="text-sm font-medium">Target Sektor</label>
                            <select name="sector" x-model="sector" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1">
                                <option value="0">Semua Sektor</option>
                                @for($i=1; $i<=10; $i++)
                                    <option value="{{ $i }}">Sektor {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium">Tenggat Waktu (Deadline)</label>
                            <input type="datetime-local" name="due_date" x-model="due_date" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1">
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium">Deskripsi Tugas</label>
                            <textarea name="description" x-model="description" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1 min-h-32" placeholder="Jelaskan detail tugas, format pengumpulan, dan lain-lain."></textarea>
                        </div>
                        
                        <div class="border-t border-border/50 pt-4 mt-2">
                            <h3 class="text-sm font-semibold mb-3">Lampiran File (Opsional)</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium">Jenis Lampiran</label>
                                    <select name="attachment_type" x-model="attachment_type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1">
                                        <option value="none">Tidak ada</option>
                                        <option value="link">Tautan (URL)</option>
                                        <option value="file">Unggah File</option>
                                    </select>
                                </div>
                                
                                <div x-show="attachment_type === 'link'" style="display: none;">
                                    <label class="text-sm font-medium">URL Tautan</label>
                                    <input type="url" name="attachment_link" x-model="attachment_link" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm mt-1" placeholder="https://contoh.com/materi">
                                </div>
                                
                                <div x-show="attachment_type === 'file'" style="display: none;">
                                    <label class="text-sm font-medium">Unggah File (Maks 50MB)</label>
                                    <div class="mt-2 w-full">
                                        <label for="attachment_file" 
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
                                                    
                                                    <!-- Progress Bar Container -->
                                                    <div class="w-full h-5 sm:h-6 border-[3px] border-foreground rounded-full p-[2px] bg-transparent flex items-center">
                                                        <div class="h-full bg-foreground rounded-full transition-all duration-300 ease-out" :style="`width: ${uploadProgress}%`"></div>
                                                    </div>
                                                    
                                                    <p class="text-[10px] sm:text-xs text-muted-foreground truncate w-full" x-text="fileName"></p>
                                                </div>
                                            </div>
                                            
                                            <input id="attachment_file" x-ref="fileInput" name="attachment_file" type="file" class="hidden" @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" />
                                        </label>
                                    </div>
                                    <template x-if="isEdit && attachment_type === 'file' && attachment_url && !fileName">
                                        <p class="text-xs text-muted-foreground mt-2">File saat ini: <span x-text="attachment_url.split('/').pop()" class="font-medium"></span>. Mengunggah file baru akan menggantikan file ini.</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="dialogOpen = false" :disabled="isUploading" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4 disabled:opacity-50">
                                Batal
                            </button>
                            <button type="submit" :disabled="isUploading" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 disabled:opacity-50 min-w-[140px]">
                                <span x-show="!isUploading" x-text="isEdit ? 'Simpan Perubahan' : 'Terbitkan Tugas'"></span>
                                <span x-show="isUploading" class="flex items-center gap-2" style="display: none;">
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
@endsection
