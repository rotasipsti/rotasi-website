@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    showDeleteModal: false,
    editData: { id: '', sector_number: '', sector_name: '', uuid_password: '' },
    deleteData: { id: '', sector_name: '' }
}">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Manajemen Password Sektor</h1>
            <p class="text-muted-foreground">Kelola data sektor beserta password registrasinya</p>
        </div>
        <button @click="showAddModal = true" class="bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md font-medium text-sm flex items-center transition-colors">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Tambah Sektor
        </button>
    </div>



    @if($errors->any())
        <div class="mb-6 p-4 rounded-md bg-destructive/10 border border-destructive/20 text-destructive flex items-start gap-3">
            <i data-lucide="alert-circle" class="h-5 w-5 mt-0.5 shrink-0"></i>
            <div>
                <p class="font-medium">Terdapat Kesalahan</p>
                <ul class="text-sm opacity-90 list-disc list-inside mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-card border border-border/50 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-muted-foreground uppercase bg-muted/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Nomor Sektor</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Sektor</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Password</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($passwords as $sector)
                    <tr class="hover:bg-muted/50 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $sector->sector_number }}</td>
                        <td class="px-6 py-4">{{ $sector->sector_name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-secondary/50 rounded text-xs font-mono border border-border/50">
                                {{ $sector->uuid_password }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editData = { id: {{ $sector->id }}, sector_number: '{{ $sector->sector_number }}', sector_name: '{{ addslashes($sector->sector_name) }}', uuid_password: '{{ addslashes($sector->uuid_password) }}' }; showEditModal = true" 
                                    class="p-2 text-muted-foreground hover:text-primary transition-colors bg-card hover:bg-muted rounded-md border border-transparent hover:border-border/50" title="Edit">
                                    <i data-lucide="edit-2" class="h-4 w-4"></i>
                                </button>
                                <button @click="deleteData = { id: {{ $sector->id }}, sector_name: '{{ addslashes($sector->sector_name) }}' }; showDeleteModal = true" 
                                    class="p-2 text-muted-foreground hover:text-destructive transition-colors bg-card hover:bg-muted rounded-md border border-transparent hover:border-border/50" title="Hapus">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-muted-foreground">
                            Belum ada data sektor. Silakan tambah data baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Sektor -->
    <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-background/80 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showAddModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-card border border-border/50 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('admin.passwords.store') }}" method="POST">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium leading-6 text-foreground" id="modal-title">Tambah Sektor Baru</h3>
                            <p class="text-sm text-muted-foreground mt-1">Masukkan informasi sektor dan password registrasinya.</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">Nomor Sektor</label>
                                <input type="number" name="sector_number" required min="1" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">Nama Sektor</label>
                                <input type="text" name="sector_name" required placeholder="Contoh: Sektor 11" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">Password</label>
                                <div class="flex gap-2">
                                    <input type="text" name="uuid_password" id="add_uuid_password" required class="flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <button type="button" onclick="document.getElementById('add_uuid_password').value = Math.random().toString(36).substring(2, 10)" class="px-3 py-2 bg-secondary text-secondary-foreground rounded-md text-sm hover:bg-secondary/80 border border-border/50">
                                        Generate
                                    </button>
                                </div>
                                <p class="text-xs text-muted-foreground mt-1">Gunakan tombol Generate untuk membuat password acak 8 karakter.</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-muted/50 sm:px-6 sm:flex sm:flex-row-reverse border-t border-border/50">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-primary-foreground hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-input shadow-sm px-4 py-2 bg-background text-base font-medium text-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Sektor -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-background/80 backdrop-blur-sm" @click="showEditModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showEditModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-card border border-border/50 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="'{{ route('admin.passwords.index') }}/' + editData.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium leading-6 text-foreground">Edit Sektor</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">Nomor Sektor</label>
                                <input type="number" name="sector_number" x-model="editData.sector_number" required min="1" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">Nama Sektor</label>
                                <input type="text" name="sector_name" x-model="editData.sector_name" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">Password</label>
                                <div class="flex gap-2">
                                    <input type="text" name="uuid_password" id="edit_uuid_password" x-model="editData.uuid_password" required class="flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <button type="button" @click="editData.uuid_password = Math.random().toString(36).substring(2, 10)" class="px-3 py-2 bg-secondary text-secondary-foreground rounded-md text-sm hover:bg-secondary/80 border border-border/50">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-muted/50 sm:px-6 sm:flex sm:flex-row-reverse border-t border-border/50">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-primary-foreground hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-input shadow-sm px-4 py-2 bg-background text-base font-medium text-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Sektor -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-background/80 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showDeleteModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-card border border-border/50 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="'{{ route('admin.passwords.index') }}/' + deleteData.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-destructive/10 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-6 w-6 text-destructive"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-foreground" id="modal-title">
                                    Hapus Sektor
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-muted-foreground">
                                        Apakah Anda yakin ingin menghapus sektor <span class="font-bold text-foreground" x-text="deleteData.sector_name"></span>? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-muted/50 sm:px-6 sm:flex sm:flex-row-reverse border-t border-border/50">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-destructive text-base font-medium text-destructive-foreground hover:bg-destructive/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Hapus Sektor
                        </button>
                        <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-input shadow-sm px-4 py-2 bg-background text-base font-medium text-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
