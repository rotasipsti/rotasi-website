@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-2 text-2xl font-bold">
            <i data-lucide="download" class="h-6 w-6"></i>
            Manajemen Unduhan
        </div>
    </div>

    <div class="bg-card rounded-xl border shadow-sm">
        <div class="p-6">
            <div class="bg-muted p-4 rounded-lg mb-6">
                <h4 class="font-bold mb-4">Tambah Dokumen Baru</h4>
                <form action="{{ route('admin.cms.download.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm mb-1">Judul Dokumen</label>
                            <input type="text" name="title" required class="w-full rounded-md border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Deskripsi Singkat</label>
                            <input type="text" name="desc" class="w-full rounded-md border-border bg-background">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1">URL File (Link GDrive / Dropbox)</label>
                            <input type="url" name="file_url" required class="w-full rounded-md border-border bg-background">
                        </div>
                    </div>
                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Simpan Dokumen</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-muted">
                        <tr>
                            <th class="px-6 py-3">Judul</th>
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
                                <a href="{{ $item->url }}" target="_blank" class="text-blue-500 hover:underline">Buka Link <i data-lucide="external-link" class="inline h-3 w-3"></i></a>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.cms.download.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
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
