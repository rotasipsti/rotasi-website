@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Manajemen Pengumuman</h1>
            <p class="text-muted-foreground">Kelola pengumuman untuk seluruh panitia & peserta.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Buat Pengumuman Baru
        </a>
    </div>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">Judul</th>
                        <th scope="col" class="px-6 py-4 font-medium">Target</th>
                        <th scope="col" class="px-6 py-4 font-medium">Tipe</th>
                        <th scope="col" class="px-6 py-4 font-medium">Status</th>
                        <th scope="col" class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse ($announcements as $announcement)
                    <tr class="hover:bg-muted/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-foreground">{{ $announcement->title }}</p>
                            <p class="text-xs text-muted-foreground truncate max-w-xs">{{ $announcement->message }}</p>
                        </td>
                        <td class="px-6 py-4 capitalize">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $announcement->target_role == 'semua' ? 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800' : 'bg-secondary text-secondary-foreground' }}">
                                {{ $announcement->target_role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($announcement->type == 'popup')
                                <span class="inline-flex items-center text-xs gap-1">
                                    <i data-lucide="message-square" class="h-3 w-3"></i> Popup
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs gap-1">
                                    <i data-lucide="monitor" class="h-3 w-3"></i> Running Text
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($announcement->is_active)
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50">Aktif</span>
                            @else
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                    <i data-lucide="edit-2" class="h-4 w-4"></i>
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-destructive hover:text-destructive-foreground h-8 w-8 text-destructive">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="bell-off" class="h-10 w-10 mb-3 text-muted-foreground/50"></i>
                                <p>Belum ada pengumuman.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($announcements->hasPages())
        <div class="p-4 border-t border-border/50">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
