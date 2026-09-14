@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold">{{ $title ?? 'Riwayat Izin Keluar' }}</h1>
            <p class="text-muted-foreground">Log perizinan keluar sementara saat acara berlangsung.</p>
        </div>
    </div>

    <!-- Riwayat Izin Keluar -->
    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow mt-6 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-border/50 bg-muted/20 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="font-semibold text-lg flex items-center gap-2">
                <i data-lucide="history" class="h-5 w-5 text-primary"></i> {{ $title ?? 'Riwayat Izin Keluar Anda' }}
            </h3>
            @if(in_array($user->role, ['keamanan', 'admin']) && isset($exit_permissions) && $exit_permissions->count() > 0)
            <form action="{{ route('keamanan.exit.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA riwayat izin keluar? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold bg-red-50 text-red-700 hover:bg-red-100 transition-colors shadow-sm">
                    <i data-lucide="trash-2" class="h-4 w-4 mr-2"></i> Hapus Semua
                </button>
            </form>
            @endif
        </div>
        <div class="p-0">
            @if(isset($exit_permissions) && $exit_permissions->count() > 0)
                <!-- Tampilan Mobile (Kartu) -->
                <div class="block sm:hidden divide-y divide-border/50">
                    @foreach($exit_permissions as $log)
                        <div class="p-4 space-y-3 hover:bg-muted/30 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    @if(in_array($user->role, ['keamanan', 'admin']))
                                        <p class="font-semibold text-sm">{{ $log->user->name }} <span class="text-xs font-normal text-muted-foreground">({{ $log->user->custom_id ?? $log->user->id }})</span></p>
                                    @endif
                                    <p class="text-xs text-muted-foreground flex items-center gap-1 mt-1"><i data-lucide="clock" class="h-3 w-3"></i> {{ $log->exit_time->format('d M Y, H:i') }} WIB</p>
                                </div>
                                @if(in_array($user->role, ['keamanan', 'admin']))
                                <form action="{{ route('keamanan.exit.destroy', $log->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-md border border-red-200 px-2 py-1 text-[10px] font-semibold bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                            <div class="bg-muted/30 rounded-md p-3 border border-border/50">
                                <p class="text-sm font-medium">Alasan / Keterangan:</p>
                                <p class="text-sm text-muted-foreground mt-1">{{ $log->reason }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Tampilan Desktop (Tabel) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border/50">
                            <tr>
                                @if(in_array($user->role, ['keamanan', 'admin']))
                                    <th class="px-6 py-4 font-medium">Nama</th>
                                @endif
                                <th class="px-6 py-4 font-medium">Tanggal & Waktu Keluar</th>
                                <th class="px-6 py-4 font-medium">Alasan / Keterangan</th>
                                @if(in_array($user->role, ['keamanan', 'admin']))
                                <th class="px-6 py-4 font-medium text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @foreach($exit_permissions as $log)
                                <tr class="hover:bg-muted/30 transition-colors">
                                    @if(in_array($user->role, ['keamanan', 'admin']))
                                        <td class="px-6 py-4">
                                            <div class="font-medium">{{ $log->user->name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $log->user->custom_id ?? $log->user->id }} • {{ ucfirst($log->user->role) }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="h-4 w-4 text-muted-foreground"></i> {{ $log->exit_time->format('d M Y') }}</span>
                                        <span class="flex items-center gap-1.5 text-xs text-muted-foreground mt-1"><i data-lucide="clock" class="h-3 w-3"></i> {{ $log->exit_time->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs break-words">
                                        {{ $log->reason }}
                                    </td>
                                    @if(in_array($user->role, ['keamanan', 'admin']))
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('keamanan.exit.destroy', $log->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-semibold bg-red-50 text-red-700 hover:bg-red-100 transition-colors shadow-sm">
                                                <i data-lucide="trash-2" class="h-3.5 w-3.5 mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 sm:p-12 text-center text-muted-foreground">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-muted mb-4">
                        <i data-lucide="history" class="h-8 w-8 opacity-50"></i>
                    </div>
                    <h3 class="text-lg font-medium text-foreground mb-1">Belum Ada Data</h3>
                    <p>Saat ini belum ada riwayat izin keluar yang tercatat.</p>
                </div>
            @endif
        </div>
        @if(isset($exit_permissions) && $exit_permissions->hasPages())
        <div class="p-4 border-t border-border/50 bg-muted/10">
            {{ $exit_permissions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
