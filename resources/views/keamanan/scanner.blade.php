@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Pemindai Izin Keluar</h1>
            <p class="text-muted-foreground">Pindai QR Code kepanitiaan untuk memproses izin keluar.</p>
        </div>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
            <h3 class="font-semibold text-lg flex items-center gap-2 mb-4">
                <i data-lucide="scan" class="h-5 w-5 text-primary"></i> Pemindai QR Code
            </h3>
            <div class="relative w-full mb-4 rounded-md border border-border/50 bg-muted/30 overflow-hidden">
                <div id="reader" class="w-full"></div>
                <!-- Checkmark Overlay -->
                <div id="scan-success-overlay" class="absolute inset-0 bg-black/40 flex items-center justify-center hidden z-10 transition-opacity duration-300">
                    <div class="bg-green-500 rounded-full p-4 shadow-lg transform scale-0 transition-transform duration-500 ease-out" id="scan-success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                </div>
            </div>
            <div id="scanner-status" class="text-sm text-center text-muted-foreground mt-2">Siap memindai kode QR</div>
        </div>

        <div class="rounded-xl border border-border/50 bg-card text-card-foreground shadow p-6">
            <h3 class="font-semibold text-lg flex items-center gap-2 mb-4">
                <i data-lucide="file-edit" class="h-5 w-5 text-primary"></i> Detail Informasi
            </h3>
            <form id="exit-form" action="{{ route('keamanan.exit.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="user_id">
                
                <div>
                    <label class="block text-sm font-medium mb-1 text-muted-foreground">ID Akun</label>
                    <input type="text" id="custom_id_display" readonly class="flex h-10 w-full rounded-md border border-input bg-muted/50 px-3 py-2 text-sm ring-offset-background disabled:cursor-not-allowed disabled:opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-muted-foreground">Nama Panitia</label>
                    <input type="text" id="name_display" readonly class="flex h-10 w-full rounded-md border border-input bg-muted/50 px-3 py-2 text-sm ring-offset-background disabled:cursor-not-allowed disabled:opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Alasan<span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="3" required placeholder="Contoh : Membeli makan" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"></textarea>
                </div>

                <div class="pt-2 flex flex-col gap-2">
                    <button type="submit" id="submit-btn" disabled class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <i data-lucide="save" class="mr-2 h-4 w-4"></i> Simpan Data
                    </button>
                    <button type="button" id="cancel-btn" disabled class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-10 px-4 py-2 border border-border">
                        <i data-lucide="x" class="mr-2 h-4 w-4"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isProcessing = false;
        const html5QrCode = new Html5Qrcode("reader");
        const statusDiv = document.getElementById('scanner-status');
        const overlay = document.getElementById('scan-success-overlay');
        const icon = document.getElementById('scan-success-icon');
        
        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;
            
            statusDiv.innerHTML = '<span class="text-orange-500">Mencari data pengguna...</span>';
            html5QrCode.pause(); // Pause scanning while fetching data
            
            // Sembunyikan tulisan "Scanner paused" bawaan html5-qrcode
            setTimeout(() => {
                const readerDiv = document.getElementById('reader');
                if (readerDiv) {
                    const divs = readerDiv.getElementsByTagName('div');
                    for (let div of divs) {
                        if (div.textContent === 'Scanner paused' || div.innerText === 'Scanner paused') {
                            div.style.display = 'none';
                        }
                    }
                }
            }, 10);
            
            fetch("{{ url('accounts/divisi-keamanan/user-info') }}/" + encodeURIComponent(decodedText))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan overlay checkmark
                        overlay.classList.remove('hidden');
                        setTimeout(() => {
                            icon.classList.remove('scale-0');
                            icon.classList.add('scale-100');
                        }, 50);

                        document.getElementById('user_id').value = data.user.id;
                        document.getElementById('custom_id_display').value = data.user.custom_id || data.user.id;
                        document.getElementById('name_display').value = data.user.name + ' (' + data.user.role + ')';
                        document.getElementById('submit-btn').removeAttribute('disabled');
                        document.getElementById('cancel-btn').removeAttribute('disabled');
                        statusDiv.innerHTML = '<span class="text-green-600 font-medium"><i data-lucide="check-circle" class="inline h-4 w-4"></i> Data ditemukan! Silakan isi keterangan alasan.</span>';
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        statusDiv.innerHTML = '<span class="text-red-500">Pengguna tidak ditemukan. Pindai ulang kode QR</span>';
                        setTimeout(() => { html5QrCode.resume(); isProcessing = false; }, 2000);
                    }
                })
                .catch(err => {
                    console.error(err);
                    statusDiv.innerHTML = '<span class="text-red-500">Terjadi kesalahan koneksi. Memulai ulang scanner...</span>';
                    setTimeout(() => { html5QrCode.resume(); isProcessing = false; }, 2000);
                });
        }

        document.getElementById('cancel-btn').addEventListener('click', function() {
            // Reset form dan state
            document.getElementById('exit-form').reset();
            document.getElementById('user_id').value = '';
            document.getElementById('custom_id_display').value = '';
            document.getElementById('name_display').value = '';
            
            document.getElementById('submit-btn').setAttribute('disabled', 'disabled');
            document.getElementById('cancel-btn').setAttribute('disabled', 'disabled');
            
            overlay.classList.add('hidden');
            icon.classList.remove('scale-100');
            icon.classList.add('scale-0');
            
            statusDiv.innerHTML = 'Siap memindai kode QR';
            
            html5QrCode.resume();
            isProcessing = false;
        });

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                // Gunakan kamera pertama (biasanya kamera belakang di HP, atau webcam utama di PC)
                let cameraId = devices[0].id;
                
                // Mulai pemindai secara otomatis
                html5QrCode.start(
                    cameraId, 
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    (errorMessage) => {
                        // ignore parse errors
                    }
                ).catch((err) => {
                    console.error(err);
                    statusDiv.innerHTML = '<span class="text-red-500">Gagal memulai pemindai. Pastikan browser memiliki izin kamera.</span>';
                });
            } else {
                statusDiv.innerHTML = '<span class="text-red-500">Tidak ada kamera yang ditemukan pada perangkat ini.</span>';
            }
        }).catch(err => {
            console.error(err);
            statusDiv.innerHTML = '<span class="text-red-500 flex flex-col gap-2"><span>Akses kamera ditolak atau perangkat tidak mendukung.</span><span class="text-xs">Pastikan Anda menggunakan koneksi aman (HTTPS) atau localhost, dan memberikan izin akses kamera di browser Anda.</span></span>';
        });
    });
</script>
@endsection
