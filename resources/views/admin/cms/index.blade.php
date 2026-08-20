@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
        
        <div class="flex items-center gap-2 text-2xl font-bold mb-6">
            <i data-lucide="globe" class="h-6 w-6"></i>
            Manajemen Konten Publik (CMS)
        </div>
        

        <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border/50" 
             x-data="{ tab: localStorage.getItem('cms_tab') || 'timeline' }"
             x-init="$watch('tab', val => localStorage.setItem('cms_tab', val))">
            <div class="p-6 text-card-foreground">
                <div class="flex flex-col gap-6">
                    <!-- Horizontal Tabs -->
                    <div class="w-full overflow-x-auto pb-2">
                        <div class="inline-flex h-10 sm:h-11 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground min-w-max">
                            <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Timeline & Tahapan
                            </button>
                            <button @click="tab = 'stakeholders'" :class="tab === 'stakeholders' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Stakeholders
                            </button>
                            <button @click="tab = 'division'" :class="tab === 'division' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Divisi Kepanitiaan
                            </button>
                            <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Galeri
                            </button>
                            <button @click="tab = 'testimonial'" :class="tab === 'testimonial' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Testimoni
                            </button>
                            <button @click="tab = 'about'" :class="tab === 'about' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Tentang Kami
                            </button>
                            <button @click="tab = 'pagecontent'" :class="tab === 'pagecontent' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Pengaturan Teks
                            </button>
                            <button @click="tab = 'visibility'" :class="tab === 'visibility' ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground'" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium transition-all">
                                Visibilitas Halaman
                            </button>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-1 bg-background rounded-lg border border-border/50 p-6">
                        
                        <!-- Timeline Tab -->
                        <div x-show="tab === 'timeline'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Daftar Timeline</h3>
                            </div>
                            
                            <!-- Form Tambah -->
                            <div class="bg-muted p-4 rounded-lg mb-6">
                                <h4 class="font-bold mb-4">Tambah Tahapan Baru</h4>
                                <form action="{{ route('admin.cms.timeline.store') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm mb-1">Judul</label>
                                            <input type="text" name="title" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Tanggal</label>
                                            <input type="text" name="date" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Lokasi</label>
                                            <input type="text" name="location" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Durasi</label>
                                            <input type="text" name="duration" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm mb-1">Deskripsi</label>
                                            <textarea name="desc" required class="w-full rounded-md border-border bg-background"></textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm mb-1">Kegiatan (Pisahkan dengan Enter)</label>
                                            <textarea name="activities" required class="w-full rounded-md border-border bg-background h-24"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Urutan (Angka)</label>
                                            <input type="number" name="order" value="0" class="w-full rounded-md border-border bg-background">
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Simpan</button>
                                </form>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs uppercase bg-muted">
                                        <tr>
                                            <th class="px-6 py-3">Urutan</th>
                                            <th class="px-6 py-3">Judul</th>
                                            <th class="px-6 py-3">Tanggal</th>
                                            <th class="px-6 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($timelines as $item)
                                        <tr class="border-b">
                                            <td class="px-6 py-4">{{ $item->order }}</td>
                                            <td class="px-6 py-4 font-bold">{{ $item->title }}</td>
                                            <td class="px-6 py-4">{{ $item->date }}</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.cms.timeline.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Stakeholders Tab -->
                        <div x-show="tab === 'stakeholders'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Daftar Stakeholders</h3>
                            </div>
                            
                            <!-- Form Tambah -->
                            <div class="bg-muted p-4 rounded-lg mb-6">
                                <h4 class="font-bold mb-4">Tambah Stakeholder</h4>
                                <form action="{{ route('admin.cms.stakeholder.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm mb-1">Nama</label>
                                            <input type="text" name="name" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Role (Jabatan)</label>
                                            <input type="text" name="role" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div class="md:col-span-2 p-3 bg-background border rounded">
                                            <label class="block text-sm font-bold mb-2">Tipe Gambar</label>
                                            <div class="flex gap-4 mb-2">
                                                <label><input type="radio" name="image_type" value="url" checked> URL Link</label>
                                                <label><input type="radio" name="image_type" value="file"> Upload File</label>
                                            </div>
                                            <input type="text" name="image_url" placeholder="Masukkan URL Gambar..." class="w-full rounded-md border-border bg-background mb-2">
                                            <input type="file" name="image_file" class="w-full text-sm">
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Simpan</button>
                                </form>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs uppercase bg-muted">
                                        <tr>
                                            <th class="px-6 py-3">Foto</th>
                                            <th class="px-6 py-3">Nama</th>
                                            <th class="px-6 py-3">Role</th>
                                            <th class="px-6 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stakeholders as $item)
                                        <tr class="border-b">
                                            <td class="px-6 py-4">
                                                <img src="{{ $item->image }}" class="w-10 h-10 rounded-full object-cover">
                                            </td>
                                            <td class="px-6 py-4 font-bold">{{ $item->name }}</td>
                                            <td class="px-6 py-4">{{ $item->role }}</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.cms.stakeholder.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Gallery Tab -->
                        <div x-show="tab === 'gallery'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Daftar Galeri</h3>
                            </div>
                            
                            <!-- Form Tambah -->
                            <div class="bg-muted p-4 rounded-lg mb-6">
                                <h4 class="font-bold mb-4">Tambah Foto Galeri</h4>
                                <form action="{{ route('admin.cms.gallery.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm mb-1">Tahun Kegiatan (Contoh: 2024)</label>
                                            <input type="text" name="year" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Caption / Keterangan</label>
                                            <input type="text" name="caption" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div class="p-3 bg-background border rounded">
                                            <label class="block text-sm font-bold mb-2">Tipe Gambar</label>
                                            <div class="flex gap-4 mb-2">
                                                <label><input type="radio" name="image_type" value="url" checked> URL Link</label>
                                                <label><input type="radio" name="image_type" value="file"> Upload File</label>
                                            </div>
                                            <input type="text" name="image_url" placeholder="Masukkan URL Gambar..." class="w-full rounded-md border-border bg-background mb-2">
                                            <input type="file" name="image_file" class="w-full text-sm">
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Simpan</button>
                                </form>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs uppercase bg-muted">
                                        <tr>
                                            <th class="px-6 py-3">Foto</th>
                                            <th class="px-6 py-3">Tahun</th>
                                            <th class="px-6 py-3">Caption</th>
                                            <th class="px-6 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($galleries as $item)
                                        <tr class="border-b">
                                            <td class="px-6 py-4">
                                                <img src="{{ $item->image }}" class="w-16 h-10 object-cover rounded">
                                            </td>
                                            <td class="px-6 py-4 font-bold">{{ $item->year }}</td>
                                            <td class="px-6 py-4">{{ $item->caption }}</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.cms.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Division Tab -->
                        <div x-show="tab === 'division'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Daftar Divisi Kepanitiaan</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <!-- Form Tambah Divisi -->
                                <div class="bg-muted p-4 rounded-lg">
                                    <h4 class="font-bold mb-4">Tambah Divisi Baru</h4>
                                    <form action="{{ route('admin.cms.division.store') }}" method="POST">
                                        @csrf
                                        <div class="space-y-4 mb-4">
                                            <div>
                                                <label class="block text-sm mb-1">Nama Divisi</label>
                                                <input type="text" name="name" required class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div>
                                                <label class="block text-sm mb-1">Deskripsi Singkat</label>
                                                <input type="text" name="desc" class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div>
                                                <label class="block text-sm mb-1">Urutan (Angka)</label>
                                                <input type="number" name="order" value="0" class="w-full rounded-md border-border bg-background">
                                            </div>
                                        </div>
                                        <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Simpan Divisi</button>
                                    </form>
                                </div>
                                
                                <!-- Form Tambah Anggota Divisi -->
                                <div class="bg-muted p-4 rounded-lg">
                                    <h4 class="font-bold mb-4">Tambah Anggota ke Divisi</h4>
                                    <form action="{{ route('admin.cms.division.member.store', ['division' => 0]) }}" method="POST" enctype="multipart/form-data" x-data="{ divId: '' }" x-on:submit="$event.target.action = '{{ url('/admin/cms/division') }}/' + divId + '/member'">
                                        @csrf
                                        <div class="space-y-4 mb-4">
                                            <div>
                                                <label class="block text-sm mb-1">Pilih Divisi</label>
                                                <select x-model="divId" required class="w-full rounded-md border-border bg-background">
                                                    <option value="">-- Pilih Divisi --</option>
                                                    @foreach($divisions as $div)
                                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm mb-1">Nama Anggota</label>
                                                <input type="text" name="name" required class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div>
                                                <label class="block text-sm mb-1">Role/Jabatan</label>
                                                <input type="text" name="role" required class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div class="p-3 bg-background border rounded">
                                                <label class="block text-sm font-bold mb-2">Foto Anggota</label>
                                                <div class="flex gap-4 mb-2">
                                                    <label><input type="radio" name="image_type" value="url" checked> URL Link</label>
                                                    <label><input type="radio" name="image_type" value="file"> Upload File</label>
                                                </div>
                                                <input type="text" name="image_url" placeholder="URL Foto..." class="w-full rounded-md border-border bg-background mb-2">
                                                <input type="file" name="image_file" class="w-full text-sm">
                                            </div>
                                        </div>
                                        <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Tambah Anggota</button>
                                    </form>
                                </div>
                            </div>

                            <!-- List Divisi & Anggota -->
                            <div class="space-y-6">
                                @foreach($divisions as $div)
                                <div class="border rounded-lg p-4 bg-card">
                                    <div class="flex justify-between items-center mb-4 pb-2 border-b">
                                        <div>
                                            <h4 class="font-bold text-lg">{{ $div->name }}</h4>
                                            <p class="text-sm text-muted-foreground">{{ $div->desc }}</p>
                                        </div>
                                        <form action="{{ route('admin.cms.division.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Hapus divisi ini beserta semua anggotanya?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-destructive hover:underline text-sm"><i data-lucide="trash-2" class="h-4 w-4 inline"></i> Hapus Divisi</button>
                                        </form>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($div->members as $member)
                                        <div class="flex items-center gap-3 bg-muted/50 p-2 rounded-md border">
                                            <img src="{{ $member->image }}" class="w-10 h-10 rounded-full object-cover">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold truncate">{{ $member->name }}</p>
                                                <p class="text-xs text-muted-foreground truncate">{{ $member->role }}</p>
                                            </div>
                                            <form action="{{ route('admin.cms.division.member.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Hapus anggota?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-destructive p-1 hover:bg-destructive/10 rounded"><i data-lucide="x" class="h-4 w-4"></i></button>
                                            </form>
                                        </div>
                                        @endforeach
                                        @if($div->members->isEmpty())
                                            <p class="text-sm text-muted-foreground italic">Belum ada anggota.</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Testimonial Tab -->
                        <div x-show="tab === 'testimonial'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Daftar Testimoni</h3>
                            </div>
                            
                            <div class="bg-muted p-4 rounded-lg mb-6">
                                <h4 class="font-bold mb-4">Tambah Testimoni</h4>
                                <form action="{{ route('admin.cms.testimonial.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm mb-1">Nama</label>
                                            <input type="text" name="name" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div>
                                            <label class="block text-sm mb-1">Role / Angkatan</label>
                                            <input type="text" name="role" required class="w-full rounded-md border-border bg-background">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm mb-1">Pesan Testimoni</label>
                                            <textarea name="message" required class="w-full rounded-md border-border bg-background h-24"></textarea>
                                        </div>
                                        <div class="md:col-span-2 p-3 bg-background border rounded">
                                            <label class="block text-sm font-bold mb-2">Foto / Avatar</label>
                                            <div class="flex gap-4 mb-2">
                                                <label><input type="radio" name="image_type" value="url" checked> URL Link</label>
                                                <label><input type="radio" name="image_type" value="file"> Upload File</label>
                                            </div>
                                            <input type="text" name="image_url" placeholder="Masukkan URL Foto..." class="w-full rounded-md border-border bg-background mb-2">
                                            <input type="file" name="image_file" class="w-full text-sm">
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md">Simpan Testimoni</button>
                                </form>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs uppercase bg-muted">
                                        <tr>
                                            <th class="px-6 py-3">Nama</th>
                                            <th class="px-6 py-3">Pesan</th>
                                            <th class="px-6 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($testimonials as $item)
                                        <tr class="border-b">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $item->image }}" class="w-10 h-10 rounded-full object-cover">
                                                    <div>
                                                        <p class="font-bold">{{ $item->name }}</p>
                                                        <p class="text-xs text-muted-foreground">{{ $item->role }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 italic">"{{ Str::limit($item->message, 100) }}"</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.cms.testimonial.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
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



                        <!-- About Tab -->
                        <div x-show="tab === 'about'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Tentang Kami</h3>
                            </div>
                            
                            <form action="{{ route('admin.cms.pagecontent.update') }}" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    <!-- Deskripsi Utama -->
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h3 class="font-bold text-md mb-4 text-primary">Tentang ROTASI & Sejarah</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Deskripsi Utama (Section Atas)</label>
                                                <textarea name="about_main_desc" class="w-full rounded-md border-border bg-background h-32">{{ $pageContents['about_main_desc']->value ?? '' }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Sejarah dan Filosofi</label>
                                                <textarea name="about_history" class="w-full rounded-md border-border bg-background h-48">{{ $pageContents['about_history']->value ?? '' }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Tentang HIMA PSTI UPI</label>
                                                <textarea name="about_hima" class="w-full rounded-md border-border bg-background h-32">{{ $pageContents['about_hima']->value ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nilai-Nilai Dasar -->
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h3 class="font-bold text-md mb-4 text-primary">Nilai-Nilai Dasar</h3>
                                        <div class="space-y-4">
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Nilai 1</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_val_1_title" value="{{ $pageContents['about_val_1_title']->value ?? 'Inisiatif' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_val_1_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_val_1_desc']->value ?? $pageContents['about_val_inisiatif']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Nilai 2</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_val_2_title" value="{{ $pageContents['about_val_2_title']->value ?? 'Tangguh' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_val_2_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_val_2_desc']->value ?? $pageContents['about_val_tangguh']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Nilai 3</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_val_3_title" value="{{ $pageContents['about_val_3_title']->value ?? 'Beretika' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_val_3_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_val_3_desc']->value ?? $pageContents['about_val_beretika']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Nilai 4</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_val_4_title" value="{{ $pageContents['about_val_4_title']->value ?? 'Inovatif' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_val_4_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_val_4_desc']->value ?? $pageContents['about_val_inovatif']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Nilai 5</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_val_5_title" value="{{ $pageContents['about_val_5_title']->value ?? 'Kooperatif' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_val_5_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_val_5_desc']->value ?? $pageContents['about_val_kooperatif']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tujuan Kaderisasi -->
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h3 class="font-bold text-md mb-4 text-primary">Tujuan Kaderisasi</h3>
                                        <div class="space-y-4">
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Tujuan 1</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_goal_1_title" value="{{ $pageContents['about_goal_1_title']->value ?? 'Pengenalan Lingkungan' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_goal_1_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_goal_1_desc']->value ?? $pageContents['about_goal_1']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Tujuan 2</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_goal_2_title" value="{{ $pageContents['about_goal_2_title']->value ?? 'Pembentukan Karakter' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_goal_2_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_goal_2_desc']->value ?? $pageContents['about_goal_2']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Tujuan 3</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_goal_3_title" value="{{ $pageContents['about_goal_3_title']->value ?? 'Pengembangan Keterampilan' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_goal_3_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_goal_3_desc']->value ?? $pageContents['about_goal_3']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Tujuan 4</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_goal_4_title" value="{{ $pageContents['about_goal_4_title']->value ?? 'Membangun Jaringan' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_goal_4_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_goal_4_desc']->value ?? $pageContents['about_goal_4']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 border rounded-md bg-background/50">
                                                <h4 class="font-bold mb-2">Tujuan 5</h4>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Judul</label>
                                                        <input type="text" name="about_goal_5_title" value="{{ $pageContents['about_goal_5_title']->value ?? 'Menjaga Tradisi' }}" class="w-full rounded-md border-border bg-background text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="about_goal_5_desc" class="w-full rounded-md border-border bg-background h-20 text-sm">{{ $pageContents['about_goal_5_desc']->value ?? $pageContents['about_goal_5']->value ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-primary text-primary-foreground px-6 py-2 rounded-md font-bold text-lg flex items-center gap-2 shadow-md hover:bg-primary/90">
                                        <i data-lucide="save" class="h-5 w-5"></i> Simpan Tentang Kami
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Page Content Tab -->
                        <div x-show="tab === 'pagecontent'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Pengaturan Teks Halaman</h3>
                            </div>
                            
                            <form action="{{ route('admin.cms.pagecontent.update') }}" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    <!-- Homepage Section -->
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h4 class="font-bold text-md mb-4 flex items-center gap-2"><i data-lucide="home" class="h-4 w-4"></i> Halaman Beranda</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Hero Title</label>
                                                <input type="text" name="home_hero_title" value="{{ $pageContents['home_hero_title']->value ?? '' }}" class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Hero Subtitle</label>
                                                <textarea name="home_hero_subtitle" class="w-full rounded-md border-border bg-background h-20">{{ $pageContents['home_hero_subtitle']->value ?? '' }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Judul Countdown</label>
                                                <input type="text" name="home_countdown_title" value="{{ $pageContents['home_countdown_title']->value ?? 'MENUJU ROTASI OKTOBER 2025' }}" class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Waktu Countdown (Tanggal & Jam)</label>
                                                <input type="datetime-local" name="home_countdown_date" value="{{ $pageContents['home_countdown_date']->value ?? '2025-10-01T00:00' }}" class="w-full rounded-md border-border bg-background">
                                            </div>
                                        </div>
                                    </div>
                                    


                                    <!-- Download Section -->
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h4 class="font-bold text-md mb-4 flex items-center gap-2"><i data-lucide="download" class="h-4 w-4"></i> Halaman Download</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Link Aplikasi MyROTASI (APK)</label>
                                                <input type="url" name="download_app_link" value="{{ $pageContents['download_app_link']->value ?? '' }}" class="w-full rounded-md border-border bg-background" placeholder="https://...">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Contact Section -->
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h4 class="font-bold text-md mb-4 flex items-center gap-2"><i data-lucide="phone" class="h-4 w-4"></i> Informasi Kontak</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Alamat Email</label>
                                                <input type="email" name="contact_email" value="{{ $pageContents['contact_email']->value ?? '' }}" class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Nomor Telepon / WA</label>
                                                <input type="text" name="contact_phone" value="{{ $pageContents['contact_phone']->value ?? '' }}" class="w-full rounded-md border-border bg-background">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-semibold mb-1">Alamat Lengkap</label>
                                                <textarea name="contact_address" class="w-full rounded-md border-border bg-background h-20">{{ $pageContents['contact_address']->value ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-primary text-primary-foreground px-6 py-2 rounded-md font-bold text-lg flex items-center gap-2 shadow-md hover:bg-primary/90">
                                        <i data-lucide="save" class="h-5 w-5"></i> Simpan Semua Teks
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Visibility Tab -->
                        <div x-show="tab === 'visibility'" style="display: none;">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold">Pengaturan Visibilitas Halaman Publik</h3>
                            </div>
                            
                            <form action="{{ route('admin.cms.pagecontent.update') }}" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    @php
                                        $publicPages = [
                                            'beranda' => 'Beranda',
                                            'tentang' => 'Tentang Kami',
                                            'tahapan' => 'Tahapan',
                                            'struktur' => 'Struktur / Kepanitiaan',
                                            'galeri' => 'Galeri',
                                            'download' => 'Download',
                                            'kontak' => 'Kontak'
                                        ];
                                    @endphp

                                    @foreach($publicPages as $key => $name)
                                    <div class="bg-muted/50 p-4 rounded-lg border">
                                        <h4 class="font-bold text-md mb-4">{{ $name }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-2">Tampilkan Halaman?</label>
                                                <select name="page_{{ $key }}_visible" class="w-full rounded-md border-border bg-background">
                                                    <option value="true" {{ ($pageContents['page_'.$key.'_visible']->value ?? 'true') === 'true' ? 'selected' : '' }}>Ya, Tampilkan</option>
                                                    <option value="false" {{ ($pageContents['page_'.$key.'_visible']->value ?? 'true') === 'false' ? 'selected' : '' }}>Sembunyikan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-2">Redirect URL (opsional)</label>
                                                <input type="text" name="page_{{ $key }}_redirect" value="{{ $pageContents['page_'.$key.'_redirect']->value ?? '' }}" class="w-full rounded-md border-border bg-background" placeholder="Contoh: /tentang (Kosongkan untuk Error 404)" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-primary text-primary-foreground px-6 py-2 rounded-md font-bold text-lg flex items-center gap-2 shadow-md hover:bg-primary/90">
                                        <i data-lucide="save" class="h-5 w-5"></i> Simpan Pengaturan
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
