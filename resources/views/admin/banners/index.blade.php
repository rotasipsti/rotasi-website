@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Manajemen Banner</h1>
            <p class="text-muted-foreground">Kelola banner pengumuman untuk dashboard pengguna.</p>
        </div>
        <button onclick="openModal('addBannerModal')" class="bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i> Tambah Banner
        </button>
    </div>

    <!-- Banner List -->
    <div class="bg-card text-card-foreground shadow-sm rounded-xl border border-border/50 overflow-hidden">
        <div class="p-6 border-b border-border/50">
            <h3 class="font-semibold text-lg">Daftar Banner</h3>
        </div>
        <div class="p-0">
            @if($banners->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-muted/50 border-b border-border/50">
                        <tr>
                            <th class="px-6 py-4 font-medium">Preview</th>
                            <th class="px-6 py-4 font-medium">Judul</th>
                            <th class="px-6 py-4 font-medium">Target Role</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50">
                        @foreach($banners as $banner)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-32 h-10 overflow-hidden rounded-md border border-border/50">
                                    @if($banner->type === 'upload' && $banner->image_path)
                                        <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                    @elseif($banner->type === 'link' && $banner->image_url)
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-muted flex items-center justify-center text-xs text-muted-foreground">No Image</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $banner->title ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $roles = [
                                        'all_except_admin' => 'Semua Role',
                                        'peserta' => 'Peserta',
                                        'mentor' => 'Mentor',
                                        'acara' => 'Acara',
                                        'keamanan' => 'Keamanan',
                                        'panitia' => 'Panitia',
                                        'semua_panitia' => 'Semua Panitia (Luar Peserta)'
                                    ];
                                @endphp
                                <span class="px-2 py-1 bg-secondary text-secondary-foreground rounded-md text-xs">
                                    {{ $roles[$banner->target_role] ?? $banner->target_role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($banner->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-md text-xs font-medium">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-md text-xs font-medium">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="editBanner({{ $banner->toJson() }})" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.cms.banners.destroy', $banner->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center text-muted-foreground">
                <i data-lucide="image" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                <p>Belum ada banner yang ditambahkan.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Banner Modal -->
<div id="addBannerModal" class="fixed inset-0 z-50 hidden bg-background/80 backdrop-blur-sm overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-card text-left shadow-xl transition-all sm:my-8 w-full max-w-lg border border-border/50 p-6 space-y-4">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Tambah Banner Baru</h2>
                <p class="text-sm text-muted-foreground">Tambahkan banner baru untuk ditampilkan di dashboard.</p>
            </div>
            
            <form action="{{ route('admin.cms.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" @submit.prevent="submitForm" x-data="bannerUploadForm">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="title">Judul Banner (Opsional)</label>
                    <input type="text" id="title" name="title" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Misal: Pengumuman Seleksi">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="target_role">Target Role</label>
                    <select id="target_role" name="target_role" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>
                        <option value="all_except_admin">Semua Role (Kecuali Admin)</option>
                        <option value="peserta">Peserta</option>
                        <option value="semua_panitia">Semua Panitia (Kecuali Peserta)</option>
                        <option value="mentor">Divisi Mentor</option>
                        <option value="acara">Divisi Acara</option>
                        <option value="keamanan">Divisi Keamanan</option>
                        <option value="panitia">Panitia (Umum)</option>
                    </select>
                </div>
                
                <div class="space-y-3">
                    <label class="text-sm font-medium leading-none">Sumber Gambar</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="type" value="upload" checked onchange="toggleBannerSource('add')" class="text-primary focus:ring-primary border-border">
                            Upload File
                        </label>
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="type" value="link" onchange="toggleBannerSource('add')" class="text-primary focus:ring-primary border-border">
                            Link URL
                        </label>
                    </div>
                </div>

                <div id="add_upload_container" class="space-y-2">
                    <label class="text-sm font-medium leading-none">File Gambar</label>
                    <div class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border/50 rounded-lg hover:bg-accent/50 hover:border-primary/50 transition-colors cursor-pointer group" ondragover="event.preventDefault(); this.classList.add('border-primary', 'bg-accent/50')" ondragleave="this.classList.remove('border-primary', 'bg-accent/50')" ondrop="event.preventDefault(); this.classList.remove('border-primary', 'bg-accent/50'); document.getElementById('image_file').files = event.dataTransfer.files; updateFileName('image_file', 'add_file_name');">
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName('image_file', 'add_file_name')">
                        
                        <div x-show="!isUploading" class="flex flex-col items-center justify-center pointer-events-none">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-muted-foreground mb-2 group-hover:text-primary transition-colors"></i>
                            <p class="text-sm font-medium text-muted-foreground group-hover:text-foreground transition-colors">Klik atau Drag & Drop gambar ke sini</p>
                            <p class="text-xs text-muted-foreground mt-1">Mendukung format gambar standar.</p>
                        </div>
                        
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 w-full h-full" x-show="isUploading" style="display: none;">
                            <div class="w-full max-w-[200px] sm:max-w-[250px] space-y-3 flex flex-col items-center">
                                <div class="text-2xl sm:text-3xl font-bold tracking-widest text-foreground" x-text="uploadProgress + '%'"></div>
                                <div class="w-full h-5 sm:h-6 border-[3px] border-foreground rounded-full p-[2px] bg-transparent flex items-center">
                                    <div class="h-full bg-foreground rounded-full transition-all duration-300 ease-out" :style="`width: ${uploadProgress}%`"></div>
                                </div>
                                <p class="text-[10px] sm:text-xs text-muted-foreground truncate w-full" x-text="fileName"></p>
                            </div>
                        </div>
                    </div>
                    <p id="add_file_name" class="text-sm font-semibold text-primary hidden text-center truncate"></p>
                </div>

                <div id="add_link_container" class="space-y-2 hidden">
                    <label class="text-sm font-medium leading-none" for="image_url">Link URL Gambar</label>
                    <input type="url" id="image_url" name="image_url" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="https://example.com/banner.jpg">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="action_url">Tujuan Link Banner (Opsional)</label>
                    <input type="url" id="action_url" name="action_url" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="https://example.com/pengumuman">
                    <p class="text-xs text-muted-foreground mt-1">Jika diisi, banner dapat diklik dan akan mengarah ke link ini.</p>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="h-4 w-4 rounded border-border text-primary focus:ring-primary">
                    <label for="is_active" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Aktifkan Banner
                    </label>
                </div>

                <div x-show="errorMessage" style="display: none;" class="bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 p-3 rounded-md text-sm border border-red-200 dark:border-red-800 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p x-text="errorMessage"></p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 pt-4">
                    <button type="button" onclick="closeModal('addBannerModal')" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" :disabled="isUploading" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <span x-show="!isUploading">Simpan</span>
                        <span x-show="isUploading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editBannerModal" class="fixed inset-0 z-50 hidden bg-background/80 backdrop-blur-sm overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-card text-left shadow-xl transition-all sm:my-8 w-full max-w-lg border border-border/50 p-6 space-y-4">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Edit Banner</h2>
                <p class="text-sm text-muted-foreground">Ubah informasi banner.</p>
            </div>
            
            <form id="editBannerForm" method="POST" enctype="multipart/form-data" class="space-y-4" @submit.prevent="submitForm" x-data="bannerUploadForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="edit_title">Judul Banner (Opsional)</label>
                    <input type="text" id="edit_title" name="title" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="edit_target_role">Target Role</label>
                    <select id="edit_target_role" name="target_role" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>
                        <option value="all_except_admin">Semua Role (Kecuali Admin)</option>
                        <option value="peserta">Peserta Saja</option>
                        <option value="semua_panitia">Semua Panitia (Kecuali Peserta)</option>
                        <option value="mentor">Divisi Mentor Saja</option>
                        <option value="acara">Divisi Acara Saja</option>
                        <option value="keamanan">Divisi Keamanan Saja</option>
                        <option value="panitia">Panitia (Umum) Saja</option>
                    </select>
                </div>
                
                <div class="space-y-3">
                    <label class="text-sm font-medium leading-none">Sumber Gambar</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" id="edit_type_upload" name="type" value="upload" onchange="toggleBannerSource('edit')" class="text-primary focus:ring-primary border-border">
                            Upload File
                        </label>
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" id="edit_type_link" name="type" value="link" onchange="toggleBannerSource('edit')" class="text-primary focus:ring-primary border-border">
                            Link URL
                        </label>
                    </div>
                </div>

                <div id="edit_upload_container" class="space-y-2">
                    <label class="text-sm font-medium leading-none">File Gambar Baru (Opsional)</label>
                    <div class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border/50 rounded-lg hover:bg-accent/50 hover:border-primary/50 transition-colors cursor-pointer group" ondragover="event.preventDefault(); this.classList.add('border-primary', 'bg-accent/50')" ondragleave="this.classList.remove('border-primary', 'bg-accent/50')" ondrop="event.preventDefault(); this.classList.remove('border-primary', 'bg-accent/50'); document.getElementById('edit_image_file').files = event.dataTransfer.files; updateFileName('edit_image_file', 'edit_file_name');">
                        <input type="file" id="edit_image_file" name="image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName('edit_image_file', 'edit_file_name')">
                        
                        <div x-show="!isUploading" class="flex flex-col items-center justify-center pointer-events-none">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-muted-foreground mb-2 group-hover:text-primary transition-colors"></i>
                            <p class="text-sm font-medium text-muted-foreground group-hover:text-foreground transition-colors">Klik atau Drag & Drop gambar ke sini</p>
                            <p class="text-xs text-muted-foreground mt-1">Mendukung format gambar standar.</p>
                        </div>
                        
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 w-full h-full" x-show="isUploading" style="display: none;">
                            <div class="w-full max-w-[200px] sm:max-w-[250px] space-y-3 flex flex-col items-center">
                                <div class="text-2xl sm:text-3xl font-bold tracking-widest text-foreground" x-text="uploadProgress + '%'"></div>
                                <div class="w-full h-5 sm:h-6 border-[3px] border-foreground rounded-full p-[2px] bg-transparent flex items-center">
                                    <div class="h-full bg-foreground rounded-full transition-all duration-300 ease-out" :style="`width: ${uploadProgress}%`"></div>
                                </div>
                                <p class="text-[10px] sm:text-xs text-muted-foreground truncate w-full" x-text="fileName"></p>
                            </div>
                        </div>
                    </div>
                    <p id="edit_file_name" class="text-sm font-semibold text-primary hidden text-center truncate"></p>
                    <p class="text-xs text-muted-foreground mt-1">Kosongkan jika tidak ingin mengubah gambar saat ini.</p>
                </div>

                <div id="edit_link_container" class="space-y-2 hidden">
                    <label class="text-sm font-medium leading-none" for="edit_image_url">Link URL Gambar</label>
                    <input type="url" id="edit_image_url" name="image_url" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="edit_action_url">Tujuan Link Banner (Opsional)</label>
                    <input type="url" id="edit_action_url" name="action_url" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    <p class="text-xs text-muted-foreground mt-1">Jika diisi, banner dapat diklik dan akan mengarah ke link ini.</p>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="h-4 w-4 rounded border-border text-primary focus:ring-primary">
                    <label for="edit_is_active" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Aktifkan Banner
                    </label>
                </div>

                <div x-show="errorMessage" style="display: none;" class="bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 p-3 rounded-md text-sm border border-red-200 dark:border-red-800 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p x-text="errorMessage"></p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 pt-4">
                    <button type="button" onclick="closeModal('editBannerModal')" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" :disabled="isUploading" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <span x-show="!isUploading">Simpan Perubahan</span>
                        <span x-show="isUploading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function toggleBannerSource(mode) {
        const type = document.querySelector(`input[name="type"]:checked`).value;
        if (mode === 'edit') {
            const editType = document.querySelector(`input[name="type"][id^="edit_type_"]:checked`).value;
            if (editType === 'upload') {
                document.getElementById('edit_upload_container').classList.remove('hidden');
                document.getElementById('edit_link_container').classList.add('hidden');
                document.getElementById('edit_image_file').required = false; // it's optional on edit
                document.getElementById('edit_image_url').required = false;
            } else {
                document.getElementById('edit_upload_container').classList.add('hidden');
                document.getElementById('edit_link_container').classList.remove('hidden');
                document.getElementById('edit_image_file').required = false;
                document.getElementById('edit_image_url').required = true;
            }
        } else {
            if (type === 'upload') {
                document.getElementById('add_upload_container').classList.remove('hidden');
                document.getElementById('add_link_container').classList.add('hidden');
                document.getElementById('image_file').required = true;
                document.getElementById('image_url').required = false;
            } else {
                document.getElementById('add_upload_container').classList.add('hidden');
                document.getElementById('add_link_container').classList.remove('hidden');
                document.getElementById('image_file').required = false;
                document.getElementById('image_url').required = true;
            }
        }
    }

    function editBanner(banner) {
        document.getElementById('editBannerForm').action = `/accounts/admin/cms/banners/${banner.id}`;
        document.getElementById('edit_title').value = banner.title || '';
        document.getElementById('edit_target_role').value = banner.target_role;
        document.getElementById('edit_action_url').value = banner.action_url || '';
        document.getElementById('edit_is_active').checked = banner.is_active == 1;
        
        if (banner.type === 'link') {
            document.getElementById('edit_type_link').checked = true;
            document.getElementById('edit_image_url').value = banner.image_url;
        } else {
            document.getElementById('edit_type_upload').checked = true;
        }
        
        toggleBannerSource('edit');
        openModal('editBannerModal');
    }

    function updateFileName(inputId, displayId) {
        const input = document.getElementById(inputId);
        const display = document.getElementById(displayId);
        if (input.files && input.files.length > 0) {
            display.textContent = 'File terpilih: ' + input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
            display.textContent = '';
        }
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('bannerUploadForm', () => ({
            isUploading: false,
            uploadProgress: 0,
            fileName: '',
            errorMessage: '',
            
            submitForm(e) {
                const typeInput = e.target.querySelector('input[name="type"]:checked');
                if (typeInput && typeInput.value === 'link') {
                    e.target.submit();
                    return;
                }

                const fileInput = e.target.querySelector('input[type="file"]');
                if (!fileInput.files.length && fileInput.required) {
                    // Let HTML5 validation trigger if needed, or just return
                    e.target.reportValidity();
                    return;
                }
                if (!fileInput.files.length) {
                    e.target.submit();
                    return;
                }

                this.isUploading = true;
                this.uploadProgress = 0;
                this.errorMessage = '';
                this.fileName = fileInput.files[0].name;
                
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
                        let errorMsg = 'Terjadi kesalahan saat menyimpan banner.';
                        try {
                            let res = JSON.parse(xhr.responseText);
                            if (res.errors) {
                                errorMsg = Object.values(res.errors)[0][0];
                            } else if (res.message) {
                                errorMsg = res.message;
                            }
                        } catch(e) {}
                        this.errorMessage = errorMsg;
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
