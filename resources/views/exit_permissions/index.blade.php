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
            <h3 class="font-semibold text-lg flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="history" class="h-5 w-5 text-primary"></i> {{ $title ?? 'Riwayat Izin Keluar Anda' }}
            </h3>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                @if(in_array($user->role, ['keamanan', 'admin']))
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-muted-foreground"></i>
                        </div>
                        <input type="text" id="searchInput" class="bg-background border border-border text-foreground text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-9 p-2" placeholder="Cari nama panitia...">
                    </div>
                @endif
                @if(in_array($user->role, ['keamanan', 'admin']) && isset($exit_permissions) && $exit_permissions->count() > 0)
                <form action="{{ route('keamanan.exit.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA riwayat izin keluar? Tindakan ini tidak dapat dibatalkan.');" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-blue-900 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition-colors shadow-sm">
                        <i data-lucide="trash-2" class="h-4 w-4 mr-2"></i> Hapus Semua
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="p-0">
            @if(isset($exit_permissions) && $exit_permissions->count() > 0)
                <!-- Tampilan Mobile (Kartu) -->
                <div class="block sm:hidden divide-y divide-border/50">
                    @foreach($exit_permissions as $log)
                        <div class="p-4 space-y-3 hover:bg-muted/30 transition-colors permission-row" data-name="{{ strtolower($log->user->name) }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    @if(in_array($user->role, ['keamanan', 'admin']))
                                        <p class="font-semibold text-sm">{{ $log->user->name }} <span class="text-xs font-normal text-muted-foreground">({{ $log->user->custom_id ?? $log->user->id }})</span></p>
                                    @endif
                                    <p class="text-xs text-muted-foreground flex items-center gap-1 mt-1">
                                        <i data-lucide="log-out" class="h-3 w-3"></i> Keluar: {{ $log->exit_time->format('H:i') }} WIB
                                    </p>
                                    @if($log->return_time)
                                    <p class="text-xs text-green-600 flex items-center gap-1 mt-1">
                                        <i data-lucide="log-in" class="h-3 w-3"></i> Kembali: {{ \Carbon\Carbon::parse($log->return_time)->format('H:i') }} WIB
                                    </p>
                                    @else
                                    <p class="text-xs text-red-600 font-semibold mt-1">Belum Kembali</p>
                                    @endif
                                </div>
                                @if(in_array($user->role, ['keamanan', 'admin']))
                                <form action="{{ route('keamanan.exit.destroy', $log->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-md bg-blue-900 px-2 py-1 text-[10px] font-semibold text-white hover:bg-blue-800 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                            <div class="bg-muted/30 rounded-md p-3 border border-border/50">
                                <p class="text-sm font-medium">Alasan / Keterangan:</p>
                                <p class="text-sm text-muted-foreground mt-1">{{ $log->reason }}</p>
                            </div>
                            
                            @if(in_array($user->role, ['keamanan', 'admin']))
                                @if(!$log->return_time)
                                <button onclick="showQrModal('{{ $log->id }}')" class="w-full inline-flex items-center justify-center rounded-md border border-primary px-3 py-2 text-sm font-semibold bg-primary text-primary-foreground hover:bg-primary/90 transition-colors mt-2">
                                    <i data-lucide="qr-code" class="h-4 w-4 mr-2"></i> Tampilkan QR Kembali
                                </button>
                                @endif
                            @else
                                @if(!$log->return_time)
                                <button onclick="openScanner()" class="w-full inline-flex items-center justify-center rounded-md border border-primary/20 px-3 py-2 text-sm font-semibold bg-primary text-primary-foreground hover:bg-primary/90 transition-colors mt-2">
                                    <i data-lucide="scan-line" class="h-4 w-4 mr-2"></i> Konfirmasi Kembali (Scan QR)
                                </button>
                                @endif
                            @endif
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
                                <th class="px-6 py-4 font-medium">Waktu Keluar</th>
                                <th class="px-6 py-4 font-medium">Waktu Kembali</th>
                                <th class="px-6 py-4 font-medium">Alasan / Keterangan</th>
                                <th class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50" id="tableBody">
                            @foreach($exit_permissions as $log)
                                <tr class="hover:bg-muted/30 transition-colors permission-row" data-name="{{ strtolower($log->user->name) }}">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->return_time)
                                            <span class="flex items-center gap-1.5 text-green-600"><i data-lucide="calendar-check" class="h-4 w-4"></i> {{ \Carbon\Carbon::parse($log->return_time)->format('d M Y') }}</span>
                                            <span class="flex items-center gap-1.5 text-xs text-green-600 mt-1"><i data-lucide="clock" class="h-3 w-3"></i> {{ \Carbon\Carbon::parse($log->return_time)->format('H:i') }} WIB</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-primary px-2.5 py-0.5 text-xs font-semibold text-primary-foreground">
                                                Belum Kembali
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 max-w-xs break-words text-xs">
                                        {{ $log->reason }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex flex-col gap-2 min-w-[115px]">
                                            @if(in_array($user->role, ['keamanan', 'admin']))
                                                @if(!$log->return_time)
                                                <button onclick="showQrModal('{{ $log->id }}')" class="w-full inline-flex items-center justify-center rounded-md px-2.5 py-1.5 text-xs font-semibold bg-primary text-primary-foreground hover:bg-primary/90 transition-colors shadow-sm">
                                                    <i data-lucide="qr-code" class="h-3.5 w-3.5 mr-1"></i> Tampilkan QR
                                                </button>
                                                @endif
                                                <form action="{{ route('keamanan.exit.destroy', $log->id) }}" method="POST" class="block w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-blue-900 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-800 transition-colors shadow-sm">
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5 mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @else
                                                @if(!$log->return_time)
                                                <button onclick="openScanner()" class="w-full inline-flex items-center justify-center rounded-md px-2.5 py-1.5 text-xs font-semibold bg-primary text-primary-foreground hover:bg-primary/90 transition-colors shadow-sm">
                                                    <i data-lucide="scan-line" class="h-3.5 w-3.5 mr-1"></i> Konfirmasi
                                                </button>
                                                @else
                                                <span class="w-full inline-flex items-center justify-center rounded-md px-2.5 py-1.5 text-xs font-semibold text-muted-foreground">
                                                    <i data-lucide="check-circle" class="h-3.5 w-3.5 mr-1 text-green-500"></i> Selesai
                                                </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
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

<!-- Modal QR Code untuk Keamanan -->
<div id="qrModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-background/80 backdrop-blur-sm">
    <div class="fixed left-[50%] top-[50%] z-50 w-full max-w-sm translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg sm:rounded-lg">
        <div class="flex flex-col space-y-1.5 text-center sm:text-left mb-4">
            <h2 class="text-lg font-semibold leading-none tracking-tight">QR Code Kembali</h2>
            <p class="text-sm text-muted-foreground">Minta panitia untuk memindai QR Code ini menggunakan perangkat mereka.</p>
        </div>
        <div class="flex justify-center items-center p-4 bg-white rounded-lg border">
            <div id="qrcode-container"></div>
        </div>
        <div class="mt-6 flex">
            <button onclick="window.location.reload()" class="w-full inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 shadow-sm">
                Sudah Scan
            </button>
        </div>
    </div>
</div>

<!-- Modal Scanner untuk Panitia -->
<div id="scannerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-background/80 backdrop-blur-sm">
    <div class="fixed left-[50%] top-[50%] z-50 w-full max-w-md translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg sm:rounded-lg">
        <div class="flex flex-col space-y-1.5 text-center sm:text-left mb-4">
            <h2 class="text-lg font-semibold leading-none tracking-tight">Scan QR Keamanan</h2>
            <p class="text-sm text-muted-foreground">Arahkan kamera ke QR Code di layar divisi keamanan.</p>
        </div>
        <div class="overflow-hidden rounded-lg bg-black flex justify-center">
            <div id="reader" style="width: 100%; max-width: 400px;"></div>
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeScanner()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                Batal
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('.permission-row');
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                if (name && name.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // QR Code Generation for Keamanan
    let qrcode = null;
    function showQrModal(id) {
        document.getElementById('qrModal').classList.remove('hidden');
        document.getElementById('qrModal').classList.add('flex');
        
        const container = document.getElementById('qrcode-container');
        container.innerHTML = '';
        qrcode = new QRCode(container, {
            text: id.toString(),
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
        document.getElementById('qrModal').classList.remove('flex');
    }

    // HTML5 QR Scanner for Panitia
    let html5QrCode = null;
    
    function openScanner() {
        document.getElementById('scannerModal').classList.remove('hidden');
        document.getElementById('scannerModal').classList.add('flex');
        
        // Ensure DOM is ready
        setTimeout(() => {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            
            html5QrCode.start(
                { facingMode: "environment" }, // Prioritaskan kamera belakang
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Gagal memulai scanner:", err);
                // Jika gagal, mungkin tidak ada kamera belakang (misal di laptop), coba tanpa facingMode
                html5QrCode.start(
                    { facingMode: "user" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess,
                    onScanFailure
                ).catch(fallbackErr => {
                    alert("Gagal mengakses kamera. Pastikan browser memiliki izin akses kamera.");
                });
            });
        }, 100);
    }

    function closeScanner() {
        document.getElementById('scannerModal').classList.add('hidden');
        document.getElementById('scannerModal').classList.remove('flex');
        
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().catch(err => console.error("Gagal menghentikan scanner:", err));
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                document.getElementById('scannerModal').classList.add('hidden');
                document.getElementById('scannerModal').classList.remove('flex');
                
                processReturn(decodedText);
            }).catch(err => {
                console.error("Failed to stop scanner", err);
                processReturn(decodedText);
            });
        } else {
            processReturn(decodedText);
        }
    }
    
    function processReturn(decodedText) {
        // Confirm return
        fetch('{{ route('exit.confirm') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '<h2 class="text-xl font-bold font-inter mt-2">Berhasil</h2>',
                    html: '<p class="text-sm mt-1">' + data.message + '</p>',
                    icon: 'success',
                    confirmButtonText: 'Tutup',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                        title: 'text-foreground',
                        htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                        confirmButton: 'bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                        icon: '!border-green-500 !text-green-500 !m-0 !mx-auto'
                    },
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: '<h2 class="text-xl font-bold font-inter mt-2">Terjadi Kesalahan</h2>',
                    html: '<p class="text-sm mt-1">' + data.message + '</p>',
                    icon: 'error',
                    confirmButtonText: 'Tutup',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                        title: 'text-foreground',
                        htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                        confirmButton: 'bg-destructive text-destructive-foreground hover:bg-destructive/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                        icon: '!border-destructive !text-destructive !m-0 !mx-auto'
                    },
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: '<h2 class="text-xl font-bold font-inter mt-2">Terjadi Kesalahan</h2>',
                html: '<p class="text-sm mt-1">Gagal memproses data. Silakan coba lagi.</p>',
                icon: 'error',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-card border border-border/50 rounded-xl shadow-2xl !p-6',
                    title: 'text-foreground',
                    htmlContainer: 'text-muted-foreground !m-0 !mt-2',
                    confirmButton: 'bg-destructive text-destructive-foreground hover:bg-destructive/90 px-4 py-2 rounded-md text-sm font-medium transition-colors border-none mt-4',
                    icon: '!border-destructive !text-destructive !m-0 !mx-auto'
                },
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            });
        });
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }
</script>
@endpush
