@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Peserta Sektor {{ auth()->user()->sektor }}</h1>
        <p class="text-muted-foreground">Daftar peserta yang berada di bawah bimbingan Anda</p>
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
                            <th class="px-4 py-3">Progress Tugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peserta_list as $p)
                            @php
                                $p_subs = $submissions->where('participant_id', $p->id)->count();
                                $p_total = count($tasks);
                                $percent = $p_total > 0 ? round(($p_subs / $p_total) * 100) : 0;
                            @endphp
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                                <td class="px-4 py-3">{{ $p->nim }}</td>
                                <td class="px-4 py-3">{{ $p->email }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-secondary rounded-full h-2.5 max-w-[100px]">
                                            <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-xs text-muted-foreground">{{ $p_subs }}/{{ $p_total }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">Belum ada peserta di sektor ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
