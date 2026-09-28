@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Data Pengumpulan Tugas</h1>
        <p class="text-muted-foreground">Detail seluruh peserta yang mengumpulkan tugas dari semua sektor</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-card border border-border/50 rounded-xl p-4 mb-6 shadow-sm">
        <form action="{{ route('stakeholder.submissions') }}" method="GET" id="filterForm">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <!-- Search by Name/NIM -->
                <div class="w-full md:flex-1">
                    <label for="search" class="block text-sm font-medium mb-1">Cari Peserta</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..." class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-9">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground"></i>
                    </div>
                </div>

                <!-- Filter by Task Type -->
                <div class="w-full md:flex-1">
                    <label for="task_type" class="block text-sm font-medium mb-1">Kategori Tugas</label>
                    <select name="task_type" id="task_type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        <option value="">Semua Kategori</option>
                        @foreach($task_types as $key => $label)
                            <option value="{{ $key }}" {{ request('task_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter by Status -->
                <div class="w-full md:flex-1">
                    <label for="status" class="block text-sm font-medium mb-1">Status Keterlambatan</label>
                    <select name="status" id="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        <option value="">Semua Status</option>
                        <option value="tepat_waktu" {{ request('status') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                @if(request('search') || request('task_type') || request('status'))
                <div class="w-full md:w-auto">
                    <a href="{{ route('stakeholder.submissions') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full text-center">
                        Reset
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const selects = form.querySelectorAll('select');
            const searchInput = document.getElementById('search');

            selects.forEach(select => {
                select.addEventListener('change', () => {
                    form.submit();
                });
            });

            let timeout = null;
            searchInput.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
    </script>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-muted-foreground bg-muted uppercase border-b">
                    <tr>
                        <th class="px-6 py-4">Sektor</th>
                        <th class="px-6 py-4">Peserta</th>
                        <th class="px-6 py-4">Tugas</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Pengumpulan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr class="border-b last:border-0 hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-4 font-semibold">
                                Sektor {{ $sub->participant->sektor ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $sub->participant->name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $sub->participant->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-primary">{{ $sub->task->title }}</div>
                                <div class="text-xs text-muted-foreground">
                                    {{ ucfirst(str_replace('_', ' ', $sub->task->task_type)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $sub->status === 'evaluated' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $sub->submitted_at->format('d M Y, H:i') }}</span>
                                    @if($sub->submitted_at > $sub->task->due_date)
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 w-fit">
                                            Terlambat
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sub->file_url)
                                    <a href="{{ $sub->file_url }}" download="{{ $sub->file_name }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4">
                                        <i data-lucide="download" class="mr-2 h-4 w-4"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-xs text-muted-foreground italic">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-12 rounded-xl border-none bg-transparent text-muted-foreground">
                                    <i data-lucide="inbox" class="mx-auto h-12 w-12 mb-4 opacity-50"></i>
                                    <p>Belum ada data pengumpulan tugas dari peserta.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
