@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">Persetujuan Akun Sektor {{ auth()->user()->sektor }}</h1>
            <p class="text-muted-foreground">Daftar peserta yang mendaftar di sektor Anda dan menunggu persetujuan.</p>
        </div>
        
        @if(isset($pending_peserta) && count($pending_peserta) > 0)
        <form action="{{ route('mentor.approvals.approve-all') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui semua akun peserta yang tertunda di sektor Anda?')">
            @csrf
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-white shadow hover:bg-green-600/90 h-10 px-4">
                <i data-lucide="check-check" class="mr-2 h-4 w-4"></i> Setujui Semua
            </button>
        </form>
        @endif
    </div>

    <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-muted-foreground bg-muted uppercase border-b">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">NIM</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($pending_peserta))
                            @forelse($pending_peserta as $p)
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                                    <td class="px-4 py-3">{{ $p->nim }}</td>
                                    <td class="px-4 py-3">{{ $p->email }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <form action="{{ route('mentor.approvals.approve', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui akun peserta ini?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-white shadow hover:bg-green-600/90 h-9 px-4 py-2">
                                                    <i data-lucide="check" class="h-4 w-4 mr-2"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('mentor.approvals.reject', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak akun peserta ini? Akun akan dihapus dari sistem.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-destructive bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90 h-9 px-4 py-2">
                                                    <i data-lucide="x" class="h-4 w-4 mr-2"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">Tidak ada pendaftar baru yang menunggu persetujuan di sektor ini.</td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
