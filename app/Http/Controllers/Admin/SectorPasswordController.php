<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SectorPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectorPasswordController extends Controller
{
    public function index()
    {
        $passwords = SectorPassword::orderBy('sector_number')->get();
        return view('admin.passwords.index', compact('passwords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sector_number' => 'required|integer|unique:sector_passwords,sector_number',
            'sector_name' => 'required|string|max:100',
            'uuid_password' => 'required|string|max:50|unique:sector_passwords,uuid_password',
        ]);

        SectorPassword::create($request->all());

        return redirect()->route('admin.passwords.index')->with('success', 'Sektor berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $password = SectorPassword::findOrFail($id);
        
        $request->validate([
            'sector_number' => 'required|integer|unique:sector_passwords,sector_number,' . $password->id,
            'sector_name' => 'required|string|max:100',
            'uuid_password' => 'required|string|max:50|unique:sector_passwords,uuid_password,' . $password->id,
        ]);

        $password->update($request->all());

        return redirect()->route('admin.passwords.index')->with('success', 'Sektor berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $password = SectorPassword::findOrFail($id);
        $password->delete();
        return redirect()->route('admin.passwords.index')->with('success', 'Sektor berhasil dihapus!');
    }
}
