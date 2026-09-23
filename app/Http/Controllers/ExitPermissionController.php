<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExitPermission;

class ExitPermissionController extends Controller
{
    public function scanner()
    {
        return view('keamanan.scanner');
    }

    public function history()
    {
        $user = auth()->user();
        
        if (in_array($user->role, ['keamanan', 'admin'])) {
            $exit_permissions = ExitPermission::with('user')
                                ->orderBy('exit_time', 'desc')
                                ->paginate(15);
            $title = "Riwayat Izin Keluar (Keseluruhan)";
        } else {
            $exit_permissions = ExitPermission::where('user_id', $user->id)
                                ->orderBy('exit_time', 'desc')
                                ->paginate(15);
            $title = "Riwayat Izin Keluar Anda";
        }
        
        return view('exit_permissions.index', compact('exit_permissions', 'title', 'user'));
    }

    public function myHistory()
    {
        $user = auth()->user();
        
        if ($user->role !== 'keamanan') {
            abort(403);
        }

        $exit_permissions = ExitPermission::where('user_id', $user->id)
                            ->orderBy('exit_time', 'desc')
                            ->paginate(15);
        $title = "Riwayat Izin Keluar Anda";
        
        return view('exit_permissions.index', compact('exit_permissions', 'title', 'user'));
    }

    public function getUserInfo($id)
    {
        // $id could be custom_id or database id
        $user = User::where('custom_id', $id)->orWhere('id', $id)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'custom_id' => $user->custom_id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
        ]);

        ExitPermission::create([
            'user_id' => $request->user_id,
            'reason' => $request->reason,
            'exit_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Izin keluar berhasil dicatat.');
    }

    public function bulkDestroy(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['keamanan', 'admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        ExitPermission::query()->delete();

        return redirect()->back()->with('success', 'Semua riwayat izin keluar berhasil dihapus.');
    }

    public function destroy($id)
    {
        $exit_permission = ExitPermission::findOrFail($id);
        $exit_permission->delete();

        return redirect()->back()->with('success', 'Riwayat izin keluar berhasil dihapus.');
    }

    public function confirmReturn(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $exit_permission = ExitPermission::where('id', $request->token)
                            ->where('user_id', auth()->id())
                            ->whereNull('return_time')
                            ->first();

        if (!$exit_permission) {
            return response()->json(['success' => false, 'message' => 'Data izin tidak valid atau sudah diselesaikan.']);
        }

        $exit_permission->update(['return_time' => now()]);

        return response()->json(['success' => true, 'message' => 'Konfirmasi kembali berhasil.']);
    }

    public function exportExcel()
    {
        $user = auth()->user();
        if (!in_array($user->role, ['keamanan', 'admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $permissions = ExitPermission::with('user')->orderBy('exit_time', 'desc')->get();

        $fileName = 'riwayat_izin_keluar.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        
        return response()->streamDownload(function () use ($writer, $permissions) {
            $writer->openToFile('php://output');

            // Write header
            $headerRow = \OpenSpout\Common\Entity\Row::fromValues(
                ['ID Peserta', 'Nama', 'Divisi / Sektor', 'Waktu Keluar', 'Waktu Kembali', 'Status', 'Alasan']
            );
            $writer->addRow($headerRow);

            // Write data rows
            foreach ($permissions as $p) {
                $status = $p->return_time ? 'Sudah Kembali' : 'Belum Kembali';
                $divisi = $p->user->sektor != 0 ? 'Sektor ' . $p->user->sektor : ucfirst($p->user->role);
                
                $row = \OpenSpout\Common\Entity\Row::fromValues([
                    $p->user->custom_id ?? $p->user->id,
                    $p->user->name,
                    $divisi,
                    \Carbon\Carbon::parse($p->exit_time)->format('d M Y, H:i'),
                    $p->return_time ? \Carbon\Carbon::parse($p->return_time)->format('d M Y, H:i') : '-',
                    $status,
                    $p->reason
                ]);
                $writer->addRow($row);
            }

            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportCsv()
    {
        $user = auth()->user();
        if (!in_array($user->role, ['keamanan', 'admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=riwayat_izin_keluar.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $permissions = ExitPermission::with('user')->orderBy('exit_time', 'desc')->get();

        $callback = function() use($permissions) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['ID Peserta', 'Nama', 'Divisi / Sektor', 'Waktu Keluar', 'Waktu Kembali', 'Status', 'Alasan']);

            foreach ($permissions as $p) {
                $status = $p->return_time ? 'Sudah Kembali' : 'Belum Kembali';
                $divisi = $p->user->sektor != 0 ? 'Sektor ' . $p->user->sektor : ucfirst($p->user->role);
                fputcsv($file, [
                    $p->user->custom_id ?? $p->user->id,
                    $p->user->name,
                    $divisi,
                    \Carbon\Carbon::parse($p->exit_time)->format('d M Y, H:i'),
                    $p->return_time ? \Carbon\Carbon::parse($p->return_time)->format('d M Y, H:i') : '-',
                    $status,
                    $p->reason
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $user = auth()->user();
        if (!in_array($user->role, ['keamanan', 'admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $permissions = ExitPermission::with('user')->orderBy('exit_time', 'desc')->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exit_permissions.pdf', compact('permissions'));
        
        return $pdf->download('riwayat_izin_keluar.pdf');
    }
    public function stakeholderPanitiaHistory()
    {
        $user = auth()->user();
        if ($user->role !== 'stakeholder') abort(403);

        $exit_permissions = ExitPermission::with('user')
                            ->whereHas('user', function($q) {
                                $q->whereIn('role', ['admin', 'panitia', 'keamanan', 'acara', 'mentor', 'stakeholder']);
                            })
                            ->orderBy('exit_time', 'desc')
                            ->paginate(15);
                            
        $title = "Riwayat Izin Keluar Seluruh Panitia";
        
        return view('stakeholder.exit_history_panitia', compact('exit_permissions', 'title', 'user'));
    }
}
