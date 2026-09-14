@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8 pt-32 min-h-screen flex items-center justify-center">
    <div class="max-w-xl mx-auto w-full">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-col space-y-1.5 p-6 text-center">
                <h3 class="font-semibold leading-none tracking-tight text-2xl">Verifikasi OTP</h3>
                <p class="text-sm text-muted-foreground mt-2">
                    Silakan masukkan 6 digit kode OTP yang telah dikirimkan ke email Anda.
                </p>
            </div>
            
            <div class="p-6 pt-0">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-3 text-sm text-green-800 rounded-md bg-green-50 border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.verify') }}" class="mt-2">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="otp" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Kode OTP</label>
                            <input id="otp" type="text" name="otp" placeholder="XXXXXX" required autofocus maxlength="6" pattern="\d{6}" autocomplete="off" class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-xl text-center font-bold tracking-widest ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 mt-2 uppercase" />
                            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full mt-6">
                        Verifikasi OTP
                    </button>
                    
                    <div class="mt-4 text-center text-sm">
                        <a href="{{ route('password.request') }}" class="text-muted-foreground hover:text-primary underline-offset-4 hover:underline">
                            Kirim ulang kode OTP
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
