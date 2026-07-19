<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'mentor') {
            return redirect()->route('dashboard.mentor');
        } elseif ($user->role === 'acara') {
            return redirect()->route('dashboard.acara');
        } elseif ($user->role === 'keamanan') {
            return redirect()->route('dashboard.keamanan');
        } elseif ($user->role === 'panitia') {
            return redirect()->route('dashboard.panitia');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $data = [];
        if (!is_null($user->sektor) && $user->is_approved) {
            $data['tasks'] = Task::where('sector', $user->sektor)
                                ->orWhere('sector', 0)
                                ->orWhere('task_type', 'angkatan')
                                ->orderBy('due_date', 'asc')
                                ->get();
                                
            $data['submissions'] = TaskSubmission::where('participant_id', $user->id)
                                ->with('task')
                                ->get();
                                
            $data['mentor'] = User::where('role', 'mentor')
                                ->where('sektor', $user->sektor)
                                ->first();

            $data['downloads'] = \App\Models\Download::orderBy('order')->get();
        }
                            
        return view('peserta.dashboard', $data);
    }
    
    public function storeSector(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'peserta' || !is_null($user->sektor)) {
            abort(403);
        }

        $request->validate([
            'sektor' => 'required|integer',
            'sectorPassword' => 'required|string',
        ]);

        $sector = \App\Models\SectorPassword::where('sector_number', $request->sektor)
                    ->where('uuid_password', $request->sectorPassword)->first();

        if (!$sector) {
            return back()->with('error', 'Password sektor tidak valid.');
        }

        $sectorName = $sector->sector_name;
        $words = explode(' ', $sectorName);
        if (count($words) >= 2) {
            $prefix = strtoupper(substr($words[0], 0, 2) . substr($words[1], 0, 1));
        } else {
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $sectorName), 0, 3));
        }
        if (strlen($prefix) < 3) {
            $prefix = str_pad($prefix, 3, 'X');
        }

        // Update prefix of custom_id
        $parts = explode('-', $user->custom_id);
        if (count($parts) === 2) {
            $user->custom_id = $prefix . '-' . $parts[1];
        }

        $user->sektor = $request->sektor;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil memilih sektor. Akun Anda sedang menunggu persetujuan.');
    }
    
    public function pesertaTasks()
    {
        $user = auth()->user();
        if ($user->role !== 'peserta') abort(403);
        
        $data['tasks'] = Task::where('sector', $user->sektor)
                            ->orWhere('sector', 0)
                            ->orWhere('task_type', 'angkatan')
                            ->orderBy('due_date', 'asc')
                            ->get();
                            
        $data['submissions'] = TaskSubmission::where('participant_id', $user->id)
                            ->with('task')
                            ->get();
                            
        return view('peserta.tasks', $data);
    }

    public function pesertaSubmissions()
    {
        $user = auth()->user();
        if ($user->role !== 'peserta') abort(403);
        
        $data['submissions'] = TaskSubmission::where('participant_id', $user->id)
                            ->with('task')
                            ->get();
                            
        return view('peserta.submissions', $data);
    }

    public function pesertaDocuments()
    {
        $user = auth()->user();
        if ($user->role !== 'peserta') abort(403);
        
        $data['downloads'] = \App\Models\Download::orderBy('order')->get();
                            
        return view('peserta.downloads', $data);
    }
    
    public function mentorDashboard()
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $peserta_list = User::whereIn('role', ['peserta', 'savior'])
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
        $data['peserta_count'] = count($peserta_list);

        $data['pending_peserta_count'] = User::whereIn('role', ['peserta', 'savior'])
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', false)
                            ->count();
                            
        $data['tasks_count'] = Task::where('sector', $user->sektor)
                            ->orWhere('sector', 0)
                            ->orWhere('task_type', 'angkatan')
                            ->count();
                            
        $pesertaIds = $peserta_list->pluck('id');
        $data['submissions_late'] = TaskSubmission::whereIn('participant_id', $pesertaIds)
                            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                            ->whereColumn('task_submissions.submitted_at', '>', 'tasks.due_date')
                            ->count();
                            
        $data['recent_submissions'] = TaskSubmission::whereIn('participant_id', $pesertaIds)
                            ->with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->limit(10)
                            ->get();
                            
        return view('mentor.dashboard', $data);
    }
    
    public function mentorPeserta()
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $data['peserta_list'] = User::whereIn('role', ['peserta', 'savior'])
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
                            
        $data['tasks'] = Task::where('sector', $user->sektor)
                            ->orWhere('sector', 0)
                            ->orWhere('task_type', 'angkatan')
                            ->get();
                            
        $pesertaIds = $data['peserta_list']->pluck('id');
        $data['submissions'] = TaskSubmission::whereIn('participant_id', $pesertaIds)->get();
        
        return view('mentor.peserta', $data);
    }
    
    public function mentorApprovals()
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $data['pending_peserta'] = User::whereIn('role', ['peserta', 'savior'])
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', false)
                            ->get();
                            
        return view('mentor.approvals', $data);
    }
    
    public function mentorSubmissions()
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $peserta_list = User::whereIn('role', ['peserta', 'savior'])
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
                            
        $pesertaIds = $peserta_list->pluck('id');
        $data['submissions'] = TaskSubmission::whereIn('participant_id', $pesertaIds)
                            ->with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->get();
                            
        return view('mentor.submissions', $data);
    }
    
    public function acaraDashboard()
    {
        $user = auth()->user();
        if ($user->role !== 'acara') abort(403);
        
        $data = [];
        $data['tasks'] = Task::withCount('submissions')
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
        $data['recent_submissions'] = TaskSubmission::with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->limit(10)
                            ->get();
                            
        return view('acara.dashboard', $data);
    }

    public function acaraTasks()
    {
        $user = auth()->user();
        if ($user->role !== 'acara') abort(403);
        
        $data = [];
        $data['tasks'] = Task::withCount('submissions')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
                            
        return view('acara.tasks', $data);
    }

    public function acaraSubmissions()
    {
        $user = auth()->user();
        if ($user->role !== 'acara') abort(403);
        
        $submissions = TaskSubmission::with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->paginate(15);
                            
        return view('acara.submissions', compact('submissions'));
    }

    public function keamananDashboard()
    {
        $user = auth()->user();
        if ($user->role !== 'keamanan') abort(403);
        
        $total_exit = \App\Models\ExitPermission::count();
        $today_exit = \App\Models\ExitPermission::whereDate('exit_time', now()->toDateString())->count();
        
        return view('keamanan.dashboard', compact('total_exit', 'today_exit'));
    }

    public function panitiaDashboard()
    {
        $user = auth()->user();
        if ($user->role !== 'panitia') abort(403);
        
        $total_exit = \App\Models\ExitPermission::where('user_id', $user->id)->count();
        $today_exit = \App\Models\ExitPermission::where('user_id', $user->id)
                            ->whereDate('exit_time', now()->toDateString())
                            ->count();
        
        return view('panitia.dashboard', compact('total_exit', 'today_exit'));
    }

    public function adminDashboard()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $data = [];
        $data['total_peserta'] = User::where('role', 'peserta')->count();
        $data['total_mentor'] = User::where('role', 'mentor')->count();
        $data['total_acara'] = User::where('role', 'acara')->count();
        $data['total_keamanan'] = User::where('role', 'keamanan')->count();
        $data['total_panitia'] = User::where('role', 'panitia')->count();
        $data['total_semua_akun'] = User::count();
        $data['total_tugas'] = Task::count();
        
        return view('admin.dashboard', $data);
    }
}
