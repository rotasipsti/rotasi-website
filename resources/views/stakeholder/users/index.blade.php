@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
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
                        <a href="{{ route('stakeholder.users.index', ['role' => $role]) }}" 
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
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto mb-4">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs uppercase bg-muted text-muted-foreground">
                                <tr>
                                    <th class="px-6 py-3">ID Akun</th>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Bergabung Sejak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr class="border-b hover:bg-muted/50 transition-colors">
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
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">Tidak ada data.</td>
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
</div>
@endsection
