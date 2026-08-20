@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8 pt-32 min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto w-full">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-col space-y-1.5 p-6 text-center">
                <h3 class="font-semibold leading-none tracking-tight text-2xl">Buat Akun ROTASI</h3>
                <p class="text-sm text-muted-foreground">
                    Lengkapi data dibawah ini untuk membuat akun
                </p>
            </div>
            <div class="p-6 pt-0">
                <form method="POST" action="{{ route('register') }}" class="mt-6" x-data="{
                    step: {{ ($errors->has('password') || $errors->has('password_confirmation')) ? 3 : (($errors->has('name') || $errors->has('nim')) ? 2 : 1) }},
                    showPassword1: false,
                    showPassword2: false,
                    validateStep1() {
                        if(this.$refs.email.checkValidity()) {
                            this.step = 2;
                        } else {
                            this.$refs.email.reportValidity();
                        }
                    },
                    validateStep2() {
                        if(this.$refs.nama_lengkap.checkValidity() && this.$refs.nim.checkValidity()) {
                            this.step = 3;
                        } else {
                            if(!this.$refs.nama_lengkap.checkValidity()) this.$refs.nama_lengkap.reportValidity();
                            else this.$refs.nim.reportValidity();
                        }
                    },
                    validateStep3() {
                        this.$refs.password_confirmation.setCustomValidity('');
                        if(this.$refs.password.checkValidity() && this.$refs.password_confirmation.checkValidity()) {
                            if(this.$refs.password.value !== this.$refs.password_confirmation.value) {
                                this.$refs.password_confirmation.setCustomValidity('Konfirmasi password tidak cocok dengan password');
                                this.$refs.password_confirmation.reportValidity();
                            } else {
                                this.step = 4;
                            }
                        } else {
                            if(!this.$refs.password.checkValidity()) this.$refs.password.reportValidity();
                            else this.$refs.password_confirmation.reportValidity();
                        }
                    }
                }">
                    @csrf
                    <input type="hidden" name="userType" value="peserta">
                    
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex h-2 overflow-hidden text-xs bg-muted rounded">
                            <div :style="'width: ' + ((step/4)*100) + '%'" class="flex flex-col justify-center text-center text-white bg-primary shadow-none whitespace-nowrap transition-all duration-500"></div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        
                        <!-- Step 1: Email -->
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <div>
                                <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Email</label>
                                <input id="email" x-ref="email" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-2" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="button" @click="validateStep1()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2">
                                    Lanjut <i data-lucide="arrow-right" class="ml-2 h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Nama Lengkap & NIM -->
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <div>
                                <label for="nama_lengkap" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Nama Lengkap</label>
                                <input id="nama_lengkap" x-ref="nama_lengkap" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap sesuai identitas" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background mt-2" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <label for="nim" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">NIM</label>
                                <input id="nim" x-ref="nim" type="text" name="nim" value="{{ old('nim') }}" placeholder="Masukkan Nomor Induk Mahasiswa" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background mt-2" />
                                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" @click="step = 1" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-6 py-2">
                                    <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i> Kembali
                                </button>
                                <button type="button" @click="validateStep2()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2">
                                    Lanjut <i data-lucide="arrow-right" class="ml-2 h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Password & Confirmation -->
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <div>
                                <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Password Akun</label>
                                <div class="relative mt-2">
                                    <input id="password" x-ref="password" :type="showPassword1 ? 'text' : 'password'" name="password" placeholder="Buat password (minimal 8 karakter)" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background pr-10" />
                                    <button type="button" @click="showPassword1 = !showPassword1" class="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        <i data-lucide="eye-off" class="h-4 w-4 text-muted-foreground" x-show="showPassword1" style="display: none;"></i>
                                        <i data-lucide="eye" class="h-4 w-4 text-muted-foreground" x-show="!showPassword1"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            
                            <div class="mt-4">
                                <label for="password_confirmation" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Konfirmasi Password</label>
                                <div class="relative mt-2">
                                    <input id="password_confirmation" x-ref="password_confirmation" @input="$el.setCustomValidity('')" :type="showPassword2 ? 'text' : 'password'" name="password_confirmation" placeholder="Ketik ulang password" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background pr-10" />
                                    <button type="button" @click="showPassword2 = !showPassword2" class="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        <i data-lucide="eye-off" class="h-4 w-4 text-muted-foreground" x-show="showPassword2" style="display: none;"></i>
                                        <i data-lucide="eye" class="h-4 w-4 text-muted-foreground" x-show="!showPassword2"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" @click="step = 2" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-6 py-2">
                                    <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i> Kembali
                                </button>
                                <button type="button" @click="validateStep3()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2">
                                    Lanjut <i data-lucide="arrow-right" class="ml-2 h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Konfirmasi -->
                        <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <div class="space-y-4">
                                <div class="p-4 rounded-lg border border-border bg-muted/30 space-y-3">
                                    <h4 class="font-medium text-sm border-b pb-2 mb-3">Tinjau Data Anda</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                                        <div class="text-xs text-muted-foreground">Email</div>
                                        <div class="text-sm font-medium md:col-span-2" x-text="step === 4 && $refs.email ? $refs.email.value : '-'"></div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                                        <div class="text-xs text-muted-foreground">Nama Lengkap</div>
                                        <div class="text-sm font-medium md:col-span-2" x-text="step === 4 && $refs.nama_lengkap ? $refs.nama_lengkap.value : '-'"></div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                                        <div class="text-xs text-muted-foreground">NIM</div>
                                        <div class="text-sm font-medium md:col-span-2" x-text="step === 4 && $refs.nim ? $refs.nim.value : '-'"></div>
                                    </div>
                                </div>
                                
                                <p class="text-xs text-muted-foreground text-center">Pastikan data yang Anda masukkan sudah benar sebelum membuat akun.</p>
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" @click="step = 3" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-6 py-2">
                                    <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i> Kembali
                                </button>
                                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2">
                                    Buat Akun <i data-lucide="check-circle" class="ml-2 h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center text-sm">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-primary hover:underline">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
