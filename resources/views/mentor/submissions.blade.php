@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Submission Sektor {{ auth()->user()->sektor }}</h1>
            <p class="text-muted-foreground">Daftar tugas yang telah dikumpulkan oleh mentee Anda</p>
        </div>
        
        <a href="{{ route('mentor.submissions.download', request()->query()) }}" class="w-full md:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 shadow">
            <i data-lucide="download-cloud" class="mr-2 h-4 w-4"></i> Unduh Semua Tugas
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-card border border-border/50 rounded-xl p-4 shadow-sm">
        <form action="{{ route('mentor.submissions') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end" id="filterForm">
            <div class="flex-1 w-full">
                <label for="search" class="block text-sm font-medium text-foreground mb-1">Cari Peserta</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}" oninput="debounceSearch()" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Nama atau NIM">
                </div>
            </div>
            
            <div class="flex-1 w-full">
                <label for="task_type" class="block text-sm font-medium text-foreground mb-1">Kategori Tugas</label>
                <select name="task_type" id="task_type" onchange="document.getElementById('filterForm').submit()" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    <option value="">Semua Kategori</option>
                    @if(isset($task_types))
                        @foreach($task_types as $key => $label)
                            <option value="{{ $key }}" {{ ($task_type_filter ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="flex-1 w-full">
                <label for="status" class="block text-sm font-medium text-foreground mb-1">Status Waktu</label>
                <select name="status" id="status" onchange="document.getElementById('filterForm').submit()" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    <option value="">Semua Status</option>
                    <option value="terlambat" {{ ($status_filter ?? '') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            
            @if(request()->hasAny(['search', 'task_type', 'status']) && (request('search') != '' || request('task_type') != '' || request('status') != ''))
                <div class="w-full sm:w-auto shrink-0 flex gap-2">
                    <a href="{{ route('mentor.submissions') }}" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4">
                        Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <script>
        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        }
    </script>

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
                                    <a href="{{ $sub->file_url }}" download="{{ $sub->file_name }}" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4">
                                        <i data-lucide="download" class="mr-2 h-4 w-4"></i> 
                                        Unduh Tugas
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
