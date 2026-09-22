@extends('layouts.auth')

@section('content')
<div class="container mx-auto px-4 py-8 pt-32 min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto w-full">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
            <div class="flex flex-col space-y-1.5 p-6 text-center">
                <h3 class="font-semibold leading-none tracking-tight text-2xl flex items-center justify-center gap-2">
                    <i data-lucide="users" class="h-6 w-6"></i>
                    Register Akun ROTASI
                </h3>
                <p class="text-sm text-muted-foreground mt-2">
                    Silakan pilih role yang sesuai untuk mendaftar
                </p>
            </div>
            <div class="p-6 pt-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <a href="{{ route('register.panitia') }}" class="flex flex-col items-center justify-center p-6 border border-border/50 rounded-lg hover:border-primary hover:bg-primary/5 transition-all group">
                        <i data-lucide="user" class="h-8 w-8 mb-3 text-muted-foreground group-hover:text-primary transition-colors"></i>
                        <span class="font-medium text-lg group-hover:text-primary transition-colors">Panitia</span>
                    </a>
                    
                    <a href="{{ route('register.mentor') }}" class="flex flex-col items-center justify-center p-6 border border-border/50 rounded-lg hover:border-primary hover:bg-primary/5 transition-all group">
                        <i data-lucide="book-open" class="h-8 w-8 mb-3 text-muted-foreground group-hover:text-primary transition-colors"></i>
                        <span class="font-medium text-lg group-hover:text-primary transition-colors">Mentor</span>
                    </a>
                    
                    <a href="{{ route('register.acara') }}" class="flex flex-col items-center justify-center p-6 border border-border/50 rounded-lg hover:border-primary hover:bg-primary/5 transition-all group">
                        <i data-lucide="calendar" class="h-8 w-8 mb-3 text-muted-foreground group-hover:text-primary transition-colors"></i>
                        <span class="font-medium text-lg group-hover:text-primary transition-colors">Divisi Acara</span>
                    </a>
                    
                    <a href="{{ route('register.keamanan') }}" class="flex flex-col items-center justify-center p-6 border border-border/50 rounded-lg hover:border-primary hover:bg-primary/5 transition-all group">
                        <i data-lucide="shield-check" class="h-8 w-8 mb-3 text-muted-foreground group-hover:text-primary transition-colors"></i>
                        <span class="font-medium text-lg group-hover:text-primary transition-colors">Divisi Keamanan</span>
                    </a>
                </div>
                
                <div class="mt-8 text-center text-sm">
                    Sudah punya akun? 
                    <a href="{{ route('login.u') }}" class="text-primary hover:underline font-medium">Login disini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
