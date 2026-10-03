@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground mb-4 transition-colors">
            <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
            Kembali ke Daftar Pengumuman
        </a>
        <h1 class="text-3xl font-bold">Edit Pengumuman</h1>
        <p class="text-muted-foreground mt-1">Perbarui informasi pengumuman yang sudah ada.</p>
    </div>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Title -->
                <div class="space-y-2">
                    <label for="title" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Judul Pengumuman <span class="text-destructive">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" required autofocus
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Contoh: Pemeliharaan Sistem">
                    @error('title')
                        <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Isi Pengumuman <span class="text-destructive">*</span>
                    </label>
                    <textarea id="message" name="message" rows="4" required
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Tuliskan isi pengumuman secara detail...">{{ old('message', $announcement->message) }}</textarea>
                    @error('message')
                        <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type & Target -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Tipe Tampilan <span class="text-destructive">*</span>
                        </label>
                        <select id="type" name="type" required
                            class="flex h-9 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="popup" class="bg-background text-foreground" {{ old('type', $announcement->type) == 'popup' ? 'selected' : '' }}>Popup Alert</option>
                            <option value="running_text" class="bg-background text-foreground" {{ old('type', $announcement->type) == 'running_text' ? 'selected' : '' }}>Running Text</option>
                        </select>
                        @error('type')
                            <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="target_role" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Target <span class="text-destructive">*</span>
                        </label>
                        <select id="target_role" name="target_role" required
                            class="flex h-9 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="semua" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'semua' ? 'selected' : '' }}>Semua (Panitia & Peserta)</option>
                            <option value="seluruh_panitia" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'seluruh_panitia' ? 'selected' : '' }}>Seluruh Panitia</option>
                            <option value="peserta" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                            <option value="mentor" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="acara" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'acara' ? 'selected' : '' }}>Divisi Acara</option>
                            <option value="keamanan" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'keamanan' ? 'selected' : '' }}>Divisi Keamanan</option>
                            <option value="panitia" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'panitia' ? 'selected' : '' }}>Panitia</option>
                            <option value="stakeholder" class="bg-background text-foreground" {{ old('target_role', $announcement->target_role) == 'stakeholder' ? 'selected' : '' }}>Stakeholder</option>
                        </select>
                        @error('target_role')
                            <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Link & Link Text -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="link" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Tautan (Opsional)
                        </label>
                        <input type="url" id="link" name="link" value="{{ old('link', $announcement->link) }}" autocomplete="off"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="https://contoh.com/info-lengkap">
                        <p class="text-[0.8rem] text-muted-foreground">URL jika pengguna ingin melihat informasi lebih lanjut.</p>
                        @error('link')
                            <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="link_text" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Teks Tautan (Opsional)
                        </label>
                        <input type="text" id="link_text" name="link_text" value="{{ old('link_text', $announcement->link_text) }}" autocomplete="off"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Contoh: Klik di sini">
                        <p class="text-[0.8rem] text-muted-foreground">Teks yang akan ditampilkan pada tombol tautan.</p>
                        @error('link_text')
                            <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                        class="peer h-4 w-4 shrink-0 rounded-sm border border-primary text-primary shadow focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                    <label for="is_active" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Aktifkan pengumuman ini segera
                    </label>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
