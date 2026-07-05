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
        task_type: 'individu',
        sector: '0',
        due_date: '',
        
        openCreate() {
            this.isEdit = false;
            this.taskId = '';
            this.title = '';
            this.description = '';
            this.task_type = 'individu';
            this.sector = '0';
            this.due_date = '';
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
            this.dialogOpen = true;
        }
    }">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Daftar Tugas ({{ count($tasks) }})</h2>
            <button @click="openCreate()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 shadow">
                <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i> 
                Buat Tugas Baru
            </button>
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
                                
                                <div class="flex flex-wrap items-center gap-2">
                                    @php
                                        $taskData = [
                                            'id' => $task->id,
                                            'title' => $task->title,
                                            'description' => $task->description,
                                            'task_type' => $task->task_type,
                                            'sector' => $task->sector,
                                            'due_date_formatted' => $task->due_date->format('Y-m-d\TH:i')
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
                
                <form :action="isEdit ? '{{ url('tasks') }}/' + taskId : '{{ route('tasks.store') }}'" method="POST">
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
                        
                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="dialogOpen = false" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6">
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Terbitkan Tugas'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
