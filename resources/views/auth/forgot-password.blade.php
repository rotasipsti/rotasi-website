@extends('layouts.auth')

@section('content')
<div class="container mx-auto px-4 py-8 pt-32 min-h-screen flex items-center justify-center">
    <div class="max-w-xl mx-auto w-full">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-col space-y-1.5 p-6 text-center">
                <h3 class="font-semibold leading-none tracking-tight text-2xl">Lupa Kata Sandi</h3>
                <p class="text-sm text-muted-foreground mt-2">
                    Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan kode OTP untuk memulihkan akun Anda.
                </p>
            </div>
            
            <div class="p-6 pt-0">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-3 text-sm text-green-800 rounded-md bg-green-50 border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="mt-2">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Email Terdaftar</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 mt-2" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full mt-6">
                        Kirim Kode OTP
                    </button>
                    
                    <div class="mt-4 text-center text-sm">
                        <a href="{{ route('login') }}" class="text-muted-foreground hover:text-primary underline-offset-4 hover:underline">
                            Kembali ke halaman login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
