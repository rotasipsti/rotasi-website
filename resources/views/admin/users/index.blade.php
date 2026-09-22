@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    selectionMode: false,
    selectedUsers: [],
    selectAll: false,
    showCreateModal: false,
    showResetModal: false,
    resetUserId: null,
    resetUserName: '',
    openResetModal(id, name) {
        this.resetUserId = id;
        this.resetUserName = name;
        this.showResetModal = true;
    },
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
            this.selectedUsers = {{ json_encode($users->pluck('id')) }};
        }
        this.selectAll = !this.selectAll;
    }
}">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-2 text-2xl font-bold">
            <i data-lucide="users" class="h-6 w-6"></i>
            Data Akun
        </div>
    </div>

    <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border/50">
        <div class="p-6 text-card-foreground">
            <div class="flex flex-col gap-6">
                <!-- Horizontal Tabs -->
                <div class="w-full overflow-x-auto pb-2">
                    <div class="inline-flex h-10 sm:h-11 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground min-w-max">
                        @foreach($roles as $role)
                        <a href="{{ route('admin.users.index', ['role' => $role]) }}" 
                           class="{{ $activeRole === $role ? 'bg-background text-foreground shadow' : 'hover:bg-background/50 text-muted-foreground' }} inline-flex items-center justify-center whitespace-nowrap rounded-md px-4 py-1.5 text-sm font-medium capitalize transition-all">
                            {{ str_replace('_', ' ', $role) }} ({{ $roleCounts[$role] ?? 0 }})
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-1 bg-background rounded-lg border border-border/50 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div class="flex items-center gap-4">
                            <h3 class="text-lg font-bold capitalize">Daftar Akun: {{ str_replace('_', ' ', $activeRole) }}</h3>
                            @if(count($users) > 0 && $activeRole !== 'admin')
                                <div x-show="selectionMode" x-transition style="display: none;" class="flex items-center gap-2 bg-card border border-border/50 px-3 py-1.5 rounded-md shadow-sm">
                                    <input type="checkbox" id="selectAll" class="rounded border-input text-primary focus:ring-primary h-4 w-4 cursor-pointer" @click="toggleAll()" :checked="selectAll">
                                    <label for="selectAll" class="text-sm font-medium cursor-pointer">Pilih Semua</label>
                                </div>
                            @endif
                        </div>
                        
                        @if($activeRole !== 'admin')
                        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                            <button @click="showCreateModal = true" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-transparent bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 shadow" x-show="!selectionMode">
                                <i data-lucide="plus" class="mr-2 h-4 w-4"></i> Tambah Akun
                            </button>
                            <div x-show="selectionMode" x-transition style="display: none;" class="w-full sm:w-auto">
                                <form action="{{ route('admin.users.bulk-destroy') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun yang Anda pilih secara permanen? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <template x-for="id in selectedUsers" :key="id">
                                        <input type="hidden" name="user_ids[]" :value="id">
                                    </template>
                                    <button type="submit" :disabled="selectedUsers.length === 0" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-destructive bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 shadow disabled:opacity-50">
                                        <i data-lucide="trash-2" class="mr-2 h-4 w-4"></i> Hapus (<span x-text="selectedUsers.length"></span>)
                                    </button>
                                </form>
                            </div>
                            
                            @if(count($users) > 0)
                                <button @click="toggleSelectionMode()" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none border border-input bg-background hover:bg-accent text-foreground h-10 px-4 shadow">
                                    <i data-lucide="check-square" class="mr-2 h-4 w-4" x-show="!selectionMode"></i> 
                                    <i data-lucide="x" class="mr-2 h-4 w-4" x-show="selectionMode" style="display: none;"></i> 
                                    <span x-text="selectionMode ? 'Batal' : 'Hapus Akun'"></span>
                                </button>
                                
                                <div x-show="!selectionMode" x-transition class="w-full sm:w-auto">
                                    <form action="{{ route('admin.users.destroy-role') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('PERINGATAN! Anda akan menghapus SEMUA akun dengan role {{ str_replace('_', ' ', $activeRole) }}. Apakah Anda yakin ingin melanjutkan? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="role" value="{{ $activeRole }}">
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 shadow">
                                            <i data-lucide="alert-triangle" class="mr-2 h-4 w-4"></i> Hapus Semua Akun
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <div class="overflow-x-auto mb-4">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs uppercase bg-muted text-muted-foreground">
                                <tr>
                                    <th class="px-4 py-3 w-10" x-show="selectionMode" style="display: none;"></th>
                                    <th class="px-6 py-3">ID Akun</th>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Bergabung Sejak</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr class="border-b hover:bg-muted/50 transition-colors">
                                    <td class="px-4 py-4" x-show="selectionMode" style="display: none;">
                                        <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers" @change="selectAll = selectedUsers.length === {{ count($users) }}" class="rounded border-input text-primary focus:ring-primary h-4 w-4 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs whitespace-nowrap">{{ $user->custom_id ?? '-' }}</td>
                                    <td class="px-6 py-4 font-medium">
                                        <div class="flex items-center gap-2 whitespace-nowrap">
                                            {{ $user->name }}
                                            @if($activeRole === 'peserta' && $user->sektor)
                                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-0.5 rounded border border-purple-400">Sektor {{ $user->sektor }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->role === 'admin')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-blue-400">Sistem</span>
                                        @elseif($user->is_approved)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-green-400">Disetujui</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-yellow-400">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-muted-foreground">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($user->role !== 'admin')
                                        <div x-show="!selectionMode" class="flex justify-end gap-1">
                                            <button @click="openResetModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-md hover:bg-blue-100" title="Reset Kata Sandi">
                                                <i data-lucide="key" class="h-4 w-4"></i>
                                            </button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-destructive hover:text-destructive/80 transition-colors p-2 rounded-md hover:bg-destructive/10" title="Hapus Akun">
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-muted-foreground">Tidak ada data.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Tambah Akun -->
    <div x-show="showCreateModal" style="display: none;" class="relative z-[60]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showCreateModal" @click.away="showCreateModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-xl bg-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-border/50">
                    <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="bg-card px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold leading-6 text-foreground" id="modal-title">Tambah Akun Baru</h3>
                                <button type="button" @click="showCreateModal = false" class="text-muted-foreground hover:text-foreground">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" required autocomplete="off" data-lpignore="true" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">NIM</label>
                                    <input type="text" name="nim" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Email</label>
                                    <input type="email" name="email" required autocomplete="off" data-lpignore="true" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Password</label>
                                    <input type="text" name="password" required autocomplete="new-password" data-lpignore="true" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Role</label>
                                    <select name="role" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        <option value="peserta" {{ $activeRole === 'peserta' ? 'selected' : '' }}>Peserta</option>
                                        <option value="panitia" {{ $activeRole === 'panitia' ? 'selected' : '' }}>Panitia</option>
                                        <option value="keamanan" {{ $activeRole === 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                                        <option value="acara" {{ $activeRole === 'acara' ? 'selected' : '' }}>Acara</option>
                                        <option value="mentor" {{ $activeRole === 'mentor' ? 'selected' : '' }}>Mentor</option>
                                        <option value="stakeholder" {{ $activeRole === 'stakeholder' ? 'selected' : '' }}>Stakeholder</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Sektor (Untuk peserta dan mentor)</label>
                                    <input type="text" name="sektor" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                </div>
                            </div>
                        </div>
                        <div class="bg-muted/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-border/50">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto transition-colors">
                                Simpan & Setujui
                            </button>
                            <button @click="showCreateModal = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-background px-3 py-2 text-sm font-semibold text-foreground shadow-sm ring-1 ring-inset ring-border hover:bg-accent sm:mt-0 sm:w-auto transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div x-show="showResetModal" style="display: none;" class="relative z-[60]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showResetModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showResetModal" @click.away="showResetModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-xl bg-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-border/50">
                    <form :action="`{{ url('accounts/admin/users') }}/${resetUserId}/reset-password`" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="bg-card px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold leading-6 text-foreground" id="modal-title">Reset Kata Sandi</h3>
                                <button type="button" @click="showResetModal = false" class="text-muted-foreground hover:text-foreground">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <p class="text-sm text-muted-foreground">Masukkan kata sandi baru untuk akun <span class="font-bold text-foreground" x-text="resetUserName"></span>.</p>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kata Sandi Baru</label>
                                    <input type="text" name="password" required minlength="8" autocomplete="new-password" data-lpignore="true" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                </div>
                            </div>
                        </div>
                        <div class="bg-muted/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-border/50">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto transition-colors">
                                Reset Sandi
                            </button>
                            <button @click="showResetModal = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-background px-3 py-2 text-sm font-semibold text-foreground shadow-sm ring-1 ring-inset ring-border hover:bg-accent sm:mt-0 sm:w-auto transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
