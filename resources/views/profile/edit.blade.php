@extends('admin.layouts.app')

@section('content')
<!-- Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<!-- html2canvas for ID Card Generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
    /* Nonaktifkan global transition untuk elemen cropper agar dragging tidak delay */
    .cropper-container * {
        transition: none !important;
    }
</style>

<div x-data="{ 
    showQrModal: false, 
    showProfileForm: false, 
    showPasswordForm: false,
    showCurrentPassword: false,
    showNewPassword: false,
    showConfirmPassword: false,
    showCropModal: false,
    cropper: null,
    imageSrc: '',
    previewUrl: '',
    downloadQrCard() {
        const card = document.getElementById('qr-id-card');
        const btn = document.getElementById('btn-download-qr');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i data-lucide=\'loader-2\' class=\'mr-2 h-4 w-4 animate-spin\'></i> Memproses...';
        btn.disabled = true;

        html2canvas(card, {
            scale: 3, 
            useCORS: true, 
            backgroundColor: null
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'Digital-ID-Card-ROTASI-{{ date("Y") }}-{{ str_replace(" ", "-", ucwords(strtolower($user->name))) }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            lucide.createIcons();
        }).catch(err => {
            console.error('Error generating card', err);
            btn.innerHTML = originalText;
            btn.disabled = false;
            lucide.createIcons();
            alert('Gagal mengunduh kartu. Silakan coba lagi.');
        });
    },
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.showCropModal = true;
                this.$nextTick(() => {
                    this.initCropper();
                });
            };
            reader.readAsDataURL(file);
        }
    },
    initCropper() {
        if (this.cropper) {
            this.cropper.destroy();
        }
        const image = document.getElementById('cropper-image');
        this.cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
        });
    },
    cropImage() {
        if (this.cropper) {
            this.cropper.getCroppedCanvas({
                width: 400,
                height: 400
            }).toBlob((blob) => {
                const fileInput = document.getElementById('profile_photo');
                const fileName = fileInput.files[0]?.name || 'cropped-image.jpg';
                const file = new File([blob], fileName, { type: 'image/jpeg', lastModified: new Date().getTime() });
                
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                this.previewUrl = URL.createObjectURL(blob);
                this.showCropModal = false;
            }, 'image/jpeg', 0.9);
        }
    },
    cancelCrop() {
        this.showCropModal = false;
        document.getElementById('profile_photo').value = '';
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
    }
}" class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Profil Akun</h1>
            <p class="text-muted-foreground">Kelola informasi dan keamanan akun Anda</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div x-data x-init="Swal.fire({
            title: '<h2 class=\'text-xl font-bold font-inter mt-2\'>Berhasil</h2>',
            html: '<p class=\'text-sm mt-1\'>Profil berhasil diperbarui.</p>',
            icon: 'success',
            confirmButtonText: 'Tutup',
            buttonsStyling: false,
            customClass: {
                popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                title: 'text-foreground',
                htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                icon: '!border-green-500 !text-green-500 !m-0 !mx-auto'
            },
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
        })"></div>
    @endif
    
    @if (session('status') === 'password-updated')
        <div x-data x-init="Swal.fire({
            title: '<h2 class=\'text-xl font-bold font-inter mt-2\'>Berhasil</h2>',
            html: '<p class=\'text-sm mt-1\'>Kata sandi berhasil diperbarui.</p>',
            icon: 'success',
            confirmButtonText: 'Tutup',
            buttonsStyling: false,
            customClass: {
                popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                title: 'text-foreground',
                htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                icon: '!border-green-500 !text-green-500 !m-0 !mx-auto'
            },
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
        })"></div>
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
                <div class="p-6 border-b border-border/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <i data-lucide="user-cog" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Ubah Profil</h3>
                            <p class="text-sm text-muted-foreground">Perbarui foto, nama, dan email akun Anda.</p>
                        </div>
                    </div>
                    <button @click="showProfileForm = !showProfileForm" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-colors w-full sm:w-auto">
                        <span x-text="showProfileForm ? 'Tutup' : 'Ubah Data Akun'"></span>
                    </button>
                </div>
                <div class="p-6" x-show="showProfileForm" x-transition style="display: none;">
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
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!previewUrl">
                                        @if($user->profile_photo_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                        @else
                                            <i data-lucide="image" class="h-6 w-6"></i>
                                        @endif
                                    </template>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" @change="handleFileChange" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-primary file:text-primary-foreground file:px-3 file:py-1 file:rounded-sm file:text-xs file:font-medium file:cursor-pointer placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors">
                                    <p class="text-xs text-muted-foreground mt-1">Format: JPG, JPEG, PNG, HEIC, WEBP, JFIF, SVG, GIF (Maks. 2MB)</p>
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
                <div class="p-6 border-b border-border/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0">
                            <i data-lucide="shield-check" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Ubah Kata Sandi</h3>
                            <p class="text-sm text-muted-foreground">Pastikan akun Anda menggunakan kata sandi yang kuat.</p>
                        </div>
                    </div>
                    <button @click="showPasswordForm = !showPasswordForm" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-colors w-full sm:w-auto">
                        <span x-text="showPasswordForm ? 'Tutup' : 'Ubah Kata Sandi'"></span>
                    </button>
                </div>
                <div class="p-6" x-show="showPasswordForm" x-transition style="display: none;">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-1">
                            <label for="update_password_current_password" class="block text-sm font-medium">Kata Sandi Saat Ini</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
                                    <i data-lucide="key" class="h-4 w-4"></i>
                                </div>
                                <input :type="showCurrentPassword ? 'text' : 'password'" id="update_password_current_password" name="current_password" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-10 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" autocomplete="current-password">
                                <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground focus:outline-none transition-colors">
                                    <i data-lucide="eye" class="h-4 w-4" x-show="!showCurrentPassword"></i>
                                    <i data-lucide="eye-off" class="h-4 w-4" x-show="showCurrentPassword" style="display: none;"></i>
                                </button>
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
                                    <input :type="showNewPassword ? 'text' : 'password'" id="update_password_password" name="password" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-10 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" autocomplete="new-password">
                                    <button type="button" @click="showNewPassword = !showNewPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground focus:outline-none transition-colors">
                                        <i data-lucide="eye" class="h-4 w-4" x-show="!showNewPassword"></i>
                                        <i data-lucide="eye-off" class="h-4 w-4" x-show="showNewPassword" style="display: none;"></i>
                                    </button>
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
                                    <input :type="showConfirmPassword ? 'text' : 'password'" id="update_password_password_confirmation" name="password_confirmation" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-10 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-colors" autocomplete="new-password">
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground focus:outline-none transition-colors">
                                        <i data-lucide="eye" class="h-4 w-4" x-show="!showConfirmPassword"></i>
                                        <i data-lucide="eye-off" class="h-4 w-4" x-show="showConfirmPassword" style="display: none;"></i>
                                    </button>
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
        <div x-show="showQrModal" style="display: none;" class="relative z-[60]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                            <button id="btn-download-qr" @click="downloadQrCard()" type="button" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto transition-colors disabled:opacity-50">
                                <i data-lucide="download" class="mr-2 h-4 w-4"></i> Unduh QR
                            </button>
                            <button @click="showQrModal = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-background px-3 py-2 text-sm font-semibold text-foreground shadow-sm ring-1 ring-inset ring-border hover:bg-accent sm:mt-0 sm:w-auto transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden ID Card Template for Download -->
            <div style="position: absolute; left: -9999px; top: -9999px; pointer-events: none;">
                <div id="qr-id-card" style="width: 400px; height: 600px; position: relative; background-color: #0f172a; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden; box-sizing: border-box;"> 
                    
                    <!-- Background Decor Elements Removed as requested -->
                    <!-- Foreground Content -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 10; padding: 32px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; box-sizing: border-box;">
                        
                        <!-- Header -->
                        <div style="text-align: center; width: 100%; padding-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.1); display: block;">
                            <h2 style="font-size: 40px; font-weight: bold; text-transform: uppercase; margin: 0; color: #ffffff; font-family: 'Bebas Neue', 'Inter', sans-serif; line-height: 1; letter-spacing: 0.1em; text-align: center;">ROTASI {{ date('Y') }}</h2>
                            <p style="font-size: 12px; text-transform: uppercase; margin: 8px 0 0 0; color: #94a3b8; font-family: 'Inter', sans-serif; letter-spacing: 0.1em; text-align: center;">Digital ID Card</p>
                        </div>
                        
                        <!-- QR Code -->
                        <div style="padding: 16px; background-color: #ffffff; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border-radius: 16px; margin: auto;">
                            <img src="{{ $qrUrl }}" crossorigin="anonymous" alt="QR Code" style="width: 224px; height: 224px; object-fit: contain; display: block; margin: 0 auto;">
                        </div>
                        
                        <!-- User Info -->
                        <div style="text-align: center; width: 100%; margin-top: auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; flex-direction: column; align-items: center;">
                            <h3 style="font-size: 24px; font-weight: bold; margin: 0 0 18px 0; text-transform: uppercase; color: #ffffff; font-family: 'Inter', sans-serif; line-height: 1.2; text-align: center;">{{ $user->name }}</h3>
                            <!-- Role Badge (Manually padded) -->
                            <div style="display: inline-block; padding: 0px 13px 13px 13px; font-size: 14px; font-weight: 600; text-transform: uppercase; margin: 0 0 16px 0; color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.5); background-color: rgba(239, 68, 68, 0.15); border-radius: 9999px; font-family: 'Inter', sans-serif; line-height: 1;">
                                {{ $user->role }}
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; width: 100%; text-align: left; padding: 16px; margin-top: 6px; background-color: rgba(255,255,255,0.05); border-radius: 12px; font-family: 'Inter', sans-serif; box-sizing: border-box;">
                                <div>
                                    <p style="font-size: 10px; text-transform: uppercase; margin: 0 0 4px 0; color: #94a3b8; text-align: left;">ID Akun</p>
                                    <p style="font-size: 14px; font-family: monospace; font-weight: 600; margin: 0; color: #ffffff; text-align: left;">{{ $user->custom_id ?? $user->id }}</p>
                                </div>
                                @if($user->nim)
                                <div style="text-align: right;">
                                    <p style="font-size: 10px; text-transform: uppercase; margin: 0 0 4px 0; color: #94a3b8; text-align: right;">NIM</p>
                                    <p style="font-size: 14px; font-family: monospace; font-weight: 600; margin: 0; color: #ffffff; text-align: right;">{{ $user->nim }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Crop Image -->
    <div x-show="showCropModal" style="display: none;" class="relative z-50" aria-labelledby="crop-modal-title" role="dialog" aria-modal="true">
        <div x-show="showCropModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showCropModal" @click.away="cancelCrop()" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-xl bg-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-border/50">
                    <div class="bg-card px-4 pb-4 pt-5 sm:p-6 sm:pb-4 relative">
                        <button @click="cancelCrop()" type="button" class="absolute top-4 right-4 text-muted-foreground hover:text-foreground transition-colors rounded-sm opacity-70 ring-offset-background hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 z-10">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold leading-6 text-foreground mb-4" id="crop-modal-title">Sesuaikan Foto Profil</h3>
                            <div class="mt-2 w-full max-h-[60vh] overflow-hidden rounded-lg bg-black/10 flex justify-center items-center">
                                <img id="cropper-image" :src="imageSrc" class="max-w-full max-h-[60vh] object-contain">
                            </div>
                        </div>
                    </div>
                    <div class="bg-muted/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-border/50">
                        <button @click="cropImage()" type="button" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto">
                            <i data-lucide="crop" class="mr-2 h-4 w-4"></i> Potong & Simpan
                        </button>
                        <button @click="cancelCrop()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-background px-3 py-2 text-sm font-semibold text-foreground shadow-sm ring-1 ring-inset ring-border hover:bg-accent sm:mt-0 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
