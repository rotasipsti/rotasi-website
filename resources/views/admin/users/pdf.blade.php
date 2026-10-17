<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Akun ROTASI {{ now()->format('Y') }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            font-size: 11px;
            color: #64748b;
            margin: 0 0 10px 0;
        }
        .meta-table {
            width: 100%;
            margin-top: 8px;
            font-size: 10px;
        }
        .meta-table td {
            padding: 2px 0;
            color: #475569;
        }
        .meta-label {
            width: 100px;
            font-weight: bold;
            color: #334155;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        table.data-table td {
            padding: 6px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        .badge-warning {
            background-color: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde047;
        }
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .badge-sector {
            background-color: #f3e8ff;
            color: #6b21a8;
            border: 1px solid #d8b4fe;
        }
        .footer {
            margin-top: 25px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="header-title">Data Akun Website ROTASI {{ now()->format('Y') }}</h1>
        <div class="header-subtitle">Regenerasi Orientasi Mahasiswa PSTI</div>
        
        <!-- <h1 class="header-title">Data Akun ROTASI {{ now()->format('Y') }}</h1> -->

        <table class="meta-table">
            <tr>
                <td class="meta-label">Kategori Filter:</td>
                <td><strong>{{ $roleLabel }}</strong></td>
                <td class="meta-label" style="text-align: right; width: 120px;">Tanggal Unduh:</td>
                <td style="text-align: right; width: 140px;">{{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="meta-label">Total Akun:</td>
                <td>{{ count($users) }} Akun</td>
                <td class="meta-label" style="text-align: right;">Diunduh Oleh:</td>
                <td style="text-align: right;">{{ auth()->user()->name }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 90px;">ID Akun</th>
                <th>Nama Lengkap</th>
                <th style="width: 75px;">NIM</th>
                <th>Email</th>
                <th style="width: 70px;">Role</th>
                <th style="width: 65px;">Sektor</th>
                <!-- <th style="width: 70px;" class="text-center">Status</th> -->
                <th style="width: 95px;">Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $u->custom_id ?? '-' }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->nim ?? '-' }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ ucfirst($u->role) }}</td>
                    <td>
                        @if($u->role === 'peserta' && $u->sektor)
                            <span class="badge badge-sector">Sektor {{ $u->sektor }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <!-- <td class="text-center">
                        @if($u->role === 'admin')
                            <span class="badge badge-info">Sistem</span>
                        @elseif($u->is_approved)
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td> -->
                    <td>{{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data akun yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div>Data dalam dokumen ini diambil dari database akun website ROTASI {{ now()->format('Y') }}. Apabila terdapat kesalahan data, harap perbaiki data pada database atau minta pemilik akun untuk mengedit data akunnya.</div>
        </div>
    </div>

</body>
</html>
