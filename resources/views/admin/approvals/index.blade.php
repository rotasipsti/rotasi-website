@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    selectionMode: false,
    selectedUsers: [],
    selectAll: false,
    toggleSelectionMode() {
        this.selectionMode = !this.selectionMode;
        if (!this.selectionMode) {
            this.selectedUsers = [];
            this.selectAll = false;
        }
    },
    toggleAll() {
        if (this.selectAll) {
            this.selectedUsers = [];
        } else {
            this.selectedUsers = {{ json_encode($pendingUsers->pluck('id')) }};
        }
        this.selectAll = !this.selectAll;
    }
}">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-2xl font-bold">
                <i data-lucide="user-check" class="h-6 w-6"></i>
                Persetujuan Akun
            </div>
            @if(count($pendingUsers) > 0)
                <div x-show="selectionMode" x-transition style="display: none;" class="flex items-center gap-2 bg-card border border-border/50 px-3 py-1.5 rounded-md shadow-sm">
                    <input type="checkbox" id="selectAll" class="rounded border-input text-primary focus:ring-primary h-4 w-4 cursor-pointer" @click="toggleAll()" :checked="selectAll">
                    <label for="selectAll" class="text-sm font-medium cursor-pointer">Pilih Semua</label>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
            <div x-show="selectionMode" x-transition style="display: none;" class="w-full sm:w-auto">
                <form action="{{ route('admin.approvals.bulk-approve') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui akun yang Anda pilih?');">
                    @csrf
                    <template x-for="id in selectedUsers" :key="id">
                        <input type="hidden" name="user_ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="selectedUsers.length === 0" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 shadow disabled:opacity-50">
                        <i data-lucide="check-square" class="mr-2 h-4 w-4"></i> Setujui (<span x-text="selectedUsers.length"></span>)
                    </button>
                </form>
            </div>
            
            @if(count($pendingUsers) > 0)
                <button @click="toggleSelectionMode()" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent text-foreground h-10 px-4 shadow">
                    <i data-lucide="check-square" class="mr-2 h-4 w-4" x-show="!selectionMode"></i> 
                    <i data-lucide="x" class="mr-2 h-4 w-4" x-show="selectionMode" style="display: none;"></i> 
                    <span x-text="selectionMode ? 'Batal' : 'Setujui Akun'"></span>
                </button>
                
                <div x-show="!selectionMode" x-transition class="w-full sm:w-auto">
                    <form action="{{ route('admin.approvals.approve-all') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui SEMUA permintaan akun yang ada?');">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 shadow">
                            <i data-lucide="check-circle" class="mr-2 h-4 w-4"></i> Setujui Semua
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border/50">
        <div class="p-6 text-card-foreground">


            <p class="mb-6 text-muted-foreground">
                Daftar pemintaan pendaftaran <strong>akun panitia</strong> yang masih menunggu persetujuan Anda untuk dapat menggunakan akun.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-muted text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 w-10" x-show="selectionMode" style="display: none;"></th>
                            <th class="px-6 py-3">ID Akun</th>
                            <th class="px-6 py-3">Nama Lengkap</th>
                            <th class="px-6 py-3">NIM</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Peran</th>
                            <th class="px-6 py-3">Waktu Daftar</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                        <tr class="border-b border-border/50 hover:bg-muted/50 transition-colors">
                            <td class="px-4 py-4" x-show="selectionMode" style="display: none;">
                                <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers" @change="selectAll = selectedUsers.length === {{ count($pendingUsers) }}" class="rounded border-input text-primary focus:ring-primary h-4 w-4 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 font-mono">{{ $user->custom_id }}</td>
                            <td class="px-6 py-4 font-bold">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->nim }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'mentor')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">Mentor</span>
                                @elseif($user->role === 'acara')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-medium">Acara</span>
                                @elseif($user->role === 'keamanan')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded-full font-medium">Keamanan</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full font-medium capitalize">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $user->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2" x-show="!selectionMode">
                                    <form action="{{ route('admin.approvals.approve', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary/90 px-3 py-1.5 rounded text-xs font-medium flex items-center gap-1 shadow-sm">
                                            <i data-lucide="check" class="h-3.5 w-3.5"></i> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.approvals.reject', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak dan menghapus pendaftaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-destructive text-destructive-foreground hover:bg-destructive/90 px-3 py-1.5 rounded text-xs font-medium flex items-center gap-1 shadow-sm">
                                            <i data-lucide="x" class="h-3.5 w-3.5"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="check-circle" class="h-12 w-12 text-muted-foreground/30 mb-3"></i>
                                    <p>Tidak ada akun yang menunggu persetujuan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
