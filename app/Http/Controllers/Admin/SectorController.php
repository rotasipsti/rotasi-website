<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectorPassword;
use App\Models\User;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = SectorPassword::orderBy('sector_number', 'asc')->get();
        
        $sectorsData = [];
        
        foreach ($sectors as $sector) {
            $mentors = User::where('role', 'mentor')
                           ->where('sektor', $sector->sector_number)
                           ->get();
                           
            $peserta = User::where('role', 'peserta')
                           ->where('sektor', $sector->sector_number)
                           ->orderBy('name', 'asc')
                           ->get();
                           
            $sectorsData[] = [
                'number' => $sector->sector_number,
                'name' => $sector->sector_name,
                'mentors' => $mentors,
                'peserta' => $peserta,
                'peserta_count' => $peserta->count(),
            ];
        }

        $totalSectors = $sectors->count();

        return view('admin.sektor.index', compact('sectorsData', 'totalSectors'));
    }
}
