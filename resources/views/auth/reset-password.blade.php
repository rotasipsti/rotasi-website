@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8 pt-32 min-h-screen flex items-center justify-center">
    <div class="max-w-xl mx-auto w-full">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-col space-y-1.5 p-6 text-center">
                <h3 class="font-semibold leading-none tracking-tight text-2xl">Reset Kata Sandi</h3>
                <p class="text-sm text-muted-foreground mt-2">
                    Silakan buat kata sandi baru untuk akun Anda.
                </p>
            </div>
            
            <div class="p-6 pt-0">
                <form method="POST" action="{{ route('password.store') }}" class="mt-2">
                    @csrf
                    
                    <div class="space-y-4">
                        <div x-data="{ showPassword: false }">
                            <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Kata Sandi Baru</label>
                            <div class="relative mt-2">
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" placeholder="Masukkan kata sandi baru" required autocomplete="new-password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pr-10" />
                                
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                                    <i data-lucide="eye-off" class="h-4 w-4 text-muted-foreground" x-show="showPassword" style="display: none;"></i>
                                    <i data-lucide="eye" class="h-4 w-4 text-muted-foreground" x-show="!showPassword"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div x-data="{ showPasswordConf: false }">
                            <label for="password_confirmation" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative mt-2">
                                <input id="password_confirmation" :type="showPasswordConf ? 'text' : 'password'" name="password_confirmation" placeholder="Konfirmasi kata sandi baru" required autocomplete="new-password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pr-10" />
                                
                                <button type="button" @click="showPasswordConf = !showPasswordConf" class="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                                    <i data-lucide="eye-off" class="h-4 w-4 text-muted-foreground" x-show="showPasswordConf" style="display: none;"></i>
                                    <i data-lucide="eye" class="h-4 w-4 text-muted-foreground" x-show="!showPasswordConf"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full mt-6">
                        Reset Kata Sandi
                    </button>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
