@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ showAddModal: false }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="flex items-center gap-2 text-2xl font-bold">
            <i data-lucide="download" class="h-6 w-6"></i>
            Manajemen Dokumen/Link
        </div>
        <button @click="showAddModal = true" class="w-full sm:w-auto bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center gap-2 shadow-sm">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Data
        </button>
    </div>

    <!-- Modal Tambah Dokumen -->
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" style="display: none;">
        <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div x-show="showAddModal" x-transition.scale.95 class="bg-card text-card-foreground rounded-xl border shadow-lg w-full max-w-lg z-50 overflow-hidden relative">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="font-bold text-lg">Tambah Dokumen/Link Baru</h3>
                <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <form action="{{ route('acara.download.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Judul<span class="text-destructive">*</span></label>
                        <input type="text" name="title" required class="w-full rounded-md border-input bg-background h-10 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Deskripsi Singkat</label>
                        <input type="text" name="desc" class="w-full rounded-md border-input bg-background h-10 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Target Audien <span class="text-destructive">*</span></label>
                        <select name="target_role" required class="w-full rounded-md border-input bg-background h-10 px-3">
                            <option value="semua">Semua (Panitia & Peserta)</option>
                            <option value="seluruh_panitia">Seluruh Panitia</option>
                            <option value="peserta">Seluruh Peserta</option>
                            <option value="mentor">Mentor</option>
                            <option value="acara">Acara</option>
                            <option value="keamanan">Keamanan</option>
                            <option value="panitia">Panitia</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">URL Link/File<span class="text-destructive">*</span></label>
                        <input type="url" name="file_url" required class="w-full rounded-md border-input bg-background h-10 px-3">
                    </div>
                </div>
                <div class="p-6 border-t bg-muted/50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-md border border-input bg-background hover:bg-accent text-sm font-medium">Batal</button>
                    <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-card rounded-xl border shadow-sm">
        <div class="p-6">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-muted">
                        <tr>
                            <th class="px-6 py-3">Judul</th>
                            <th class="px-6 py-3">Target</th>
                            <th class="px-6 py-3">Link</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($downloads as $item)
                        <tr class="border-b">
                            <td class="px-6 py-4">
                                <p class="font-bold">{{ $item->title }}</p>
                                <p class="text-xs text-muted-foreground">{{ $item->desc }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-primary/10 text-primary rounded text-xs font-medium capitalize">{{ str_replace('_', ' ', $item->target_role ?? 'Semua') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $item->url }}" target="_blank" class="text-blue-500 hover:underline">Buka Link <i data-lucide="external-link" class="inline h-3 w-3"></i></a>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('acara.download.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-destructive hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
