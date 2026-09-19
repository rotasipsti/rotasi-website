<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Izin Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-returned {
            color: #15803d; /* Green */
        }
        .status-not-returned {
            color: #b91c1c; /* Red */
        }
    </style>
</head>
<body>

    <h2>Riwayat Izin Keluar Panitia ROTASI {{ now()->format('Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Divisi</th>
                <th>Waktu Keluar</th>
                <th>Waktu Kembali</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->user->name }}<br><small>{{ $log->user->custom_id ?? $log->user->id }}</small></td>
                    <td>{{ $log->user->sektor != 0 ? 'Sektor ' . $log->user->sektor : ucfirst($log->user->role) }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->exit_time)->format('d M Y, H:i') }}</td>
                    <td>
                        @if($log->return_time)
                            <span class="status-returned">{{ \Carbon\Carbon::parse($log->return_time)->format('d M Y, H:i') }}</span>
                        @else
                            <span class="status-not-returned">Belum Kembali</span>
                        @endif
                    </td>
                    <td>{{ $log->reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>

</body>
</html>
