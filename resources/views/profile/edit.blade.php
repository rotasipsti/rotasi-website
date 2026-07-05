@extends('admin.layouts.app')

@section('content')
<div x-data="{ showQrModal: false }" class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Profil Akun</h1>
            <p class="text-muted-foreground">Kelola informasi dan keamanan akun Anda</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200 text-green-700 flex items-center gap-2">
            <i data-lucide="check-circle" class="h-5 w-5"></i>
            Profil berhasil diperbarui.
        </div>
    @endif
    
    @if (session('status') === 'password-updated')
        <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200 text-green-700 flex items-center gap-2">
            <i data-lucide="check-circle" class="h-5 w-5"></i>
            Kata sandi berhasil diperbarui.
        </div>
    @endif

    <div class="flex flex-col space-y-6">
        <!-- Informasi Akun -->
            <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6 border-b border-border/50 pb-6">
                        <div class="h-16 w-16 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-2xl border-2 border-primary/30 overflow-hidden shrink-0">
                            @if($user->profile_photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                {{ substr($user->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-lg leading-tight">{{ $user->name }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $user->email }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <p class="capitalize inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-primary/10 text-primary">{{ $user->role }}</p>
                                
                                @if(in_array($user->role, ['panitia', 'acara', 'mentor', 'keamanan']))
                                    <button @click="showQrModal = true" type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-7 px-3 py-1">
                                        <i data-lucide="qr-code" class="mr-1.5 h-3.5 w-3.5"></i> Tampilkan QR
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-sm font-semibold flex items-center gap-2 mb-4 text-muted-foreground uppercase tracking-wider">
                        <i data-lucide="info" class="h-4 w-4"></i>
                        Detail Akun
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">ID Akun</p>
                            <p class="font-medium bg-muted/50 px-3 py-2 rounded-md border border-border/50 text-sm">{{ $user->custom_id ?? $user->id }}</p>
                        </div>
                        @if($user->nim)
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">NIM</p>
                            <p class="font-medium bg-muted/50 px-3 py-2 rounded-md border border-border/50 text-sm">{{ $user->nim }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Tanggal Dibuat</p>
                            <p class="font-medium bg-muted/50 px-3 py-2 rounded-md border border-border/50 text-sm">{{ $user->created_at ? $user->created_at->format('d F Y, H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        

        <!-- Edit Profil -->
            <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                <div class="p-6 border-b border-border/50 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <i data-lucide="user-cog" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Ubah Profil</h3>
                        <p class="text-sm text-muted-foreground">Perbarui foto, nama, dan email akun Anda.</p>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $role = auth()->user()->role;
                        $updateRoute = route('peserta.profile.update');
                        
                        if ($role === 'admin') {
                            $updateRoute = route('admin.profile.update');
                        } elseif ($role === 'mentor') {
                            $updateRoute = route('mentor.profile.update');
                        } elseif ($role === 'acara') {
                            $updateRoute = route('acara.profile.update');
                        } elseif ($role === 'keamanan') {
                            $updateRoute = route('keamanan.profile.update');
                        } elseif ($role === 'panitia') {
                            $updateRoute = route('panitia.profile.update');
                        }
                    @endphp
                    <form method="post" action="{{ $updateRoute }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="space-y-1">
                            <label for="profile_photo" class="block text-sm font-medium">Foto Profil</label>
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center text-muted-foreground overflow-hidden border border-border/50 shrink-0">
                                    @if($user->profile_photo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                    @else
                                        <i data-lucide="image" class="h-6 w-6"></i>
                                    @endif
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-primary file:text-primary-foreground file:px-3 file:py-1 file:rounded-sm file:text-xs file:font-medium file:cursor-pointer placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors">
                                    <p class="text-xs text-muted-foreground mt-1">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                                    @error('profile_photo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="name" class="block text-sm font-medium">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
                                        <i data-lucide="user" class="h-4 w-4"></i>
                                    </div>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" required>
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <div class="relative">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
                                        <i data-lucide="mail" class="h-4 w-4"></i>
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" required>
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border/50 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2 shadow-sm">
                                <i data-lucide="save" class="mr-2 h-4 w-4"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Password -->
            <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
                <div class="p-6 border-b border-border/50 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500">
                        <i data-lucide="shield-check" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Ubah Kata Sandi</h3>
                        <p class="text-sm text-muted-foreground">Pastikan akun Anda menggunakan kata sandi yang kuat.</p>
                    </div>
                </div>
                <div class="p-6">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-1">
                            <label for="update_password_current_password" class="block text-sm font-medium">Kata Sandi Saat Ini</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
                                    <i data-lucide="key" class="h-4 w-4"></i>
                                </div>
                                <input type="password" id="update_password_current_password" name="current_password" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" autocomplete="current-password">
                            </div>
                            @error('current_password', 'updatePassword')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="update_password_password" class="block text-sm font-medium">Kata Sandi Baru</label>
                                <div class="relative">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
                                        <i data-lucide="lock" class="h-4 w-4"></i>
                                    </div>
                                    <input type="password" id="update_password_password" name="password" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" autocomplete="new-password">
                                </div>
                                @error('password', 'updatePassword')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label for="update_password_password_confirmation" class="block text-sm font-medium">Konfirmasi Kata Sandi Baru</label>
                                <div class="relative">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
                                        <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                                    </div>
                                    <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" autocomplete="new-password">
                                </div>
                                @error('password_confirmation', 'updatePassword')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border/50 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2 shadow-sm">
                                <i data-lucide="key-round" class="mr-2 h-4 w-4"></i> Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    <!-- Modal QR Code -->
    @if(in_array($user->role, ['panitia', 'acara', 'mentor', 'keamanan']))
        <div x-show="showQrModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showQrModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="showQrModal" @click.away="showQrModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-xl bg-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-border/50">
                        <div class="bg-card px-4 pb-4 pt-5 sm:p-6 sm:pb-4 relative">
                            <button @click="showQrModal = false" class="absolute top-4 right-4 text-muted-foreground hover:text-foreground transition-colors rounded-sm opacity-70 ring-offset-background hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </button>
                            <div class="flex flex-col items-center text-center">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 mb-4">
                                    <i data-lucide="qr-code" class="h-6 w-6 text-primary"></i>
                                </div>
                                <div class="mt-1 text-center w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-foreground" id="modal-title">Kode QR Akun</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-muted-foreground">
                                            Simpan kode QR ini jika diperlukan
                                        </p>
                                    </div>
                                    <div class="mt-6 flex justify-center w-full">
                                        @php
                                            $qrData = $user->custom_id ?? $user->id;
                                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=2000x2000&data=" . urlencode($qrData);
                                        @endphp
                                        <div class="p-4 bg-white rounded-xl shadow-sm border border-border/50 inline-flex justify-center items-center">
                                            <img src="{{ $qrUrl }}" alt="QR Code {{ $user->name }}" class="w-56 h-56 sm:w-64 sm:h-64 object-contain">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-muted/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-border/50">
                            <a href="{{ $qrUrl }}&download=1" target="_blank" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto">
                                <i data-lucide="download" class="mr-2 h-4 w-4"></i> Unduh QR Code
                            </a>
                            <button @click="showQrModal = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-background px-3 py-2 text-sm font-semibold text-foreground shadow-sm ring-1 ring-inset ring-border hover:bg-accent sm:mt-0 sm:w-auto transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
