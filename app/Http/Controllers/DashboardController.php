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
        } elseif ($user->role === 'stakeholder') {
            return redirect()->route('stakeholder.dashboard');
        }

        $data = [];
        if (!is_null($user->sektor) && $user->is_approved) {
            $data['tasks'] = Task::where('is_draft', false)
                                ->where(function($query) use ($user) {
                                    $query->where('sector', $user->sektor)
                                          ->orWhere('sector', 0)
                                          ->orWhere('task_type', 'angkatan');
                                })
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
        
        $data['tasks'] = Task::where('is_draft', false)
                            ->where(function($query) use ($user) {
                                $query->where('sector', $user->sektor)
                                      ->orWhere('sector', 0)
                                      ->orWhere('task_type', 'angkatan');
                            })
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

    public function sharedDocuments()
    {
        $user = auth()->user();
        if ($user->role === 'admin') abort(403);

        $targetRoles = ['semua', $user->role];
        
        if (!in_array($user->role, ['admin', 'peserta'])) {
            $targetRoles[] = 'seluruh_panitia';
        }
        
        $data['downloads'] = \App\Models\Download::whereIn('target_role', $targetRoles)
                                ->orderBy('order')
                                ->get();
                            
        return view('peserta.downloads', $data);
    }
    
    public function mentorDashboard()
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $peserta_list = User::where('role', 'peserta')
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
        $data['peserta_count'] = count($peserta_list);

        $data['pending_peserta_count'] = User::where('role', 'peserta')
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', false)
                            ->count();
                            
        $data['tasks_count'] = Task::where('is_draft', false)
                            ->where(function($query) use ($user) {
                                $query->where('sector', $user->sektor)
                                      ->orWhere('sector', 0)
                                      ->orWhere('task_type', 'angkatan');
                            })
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
        $data['peserta_list'] = User::where('role', 'peserta')
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
                            
        $data['tasks'] = Task::where('is_draft', false)
                            ->where(function($query) use ($user) {
                                $query->where('sector', $user->sektor)
                                      ->orWhere('sector', 0)
                                      ->orWhere('task_type', 'angkatan');
                            })
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
        $data['pending_peserta'] = User::where('role', 'peserta')
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', false)
                            ->get();
                            
        return view('mentor.approvals', $data);
    }
    
    public function mentorSubmissions(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $peserta_list = User::where('role', 'peserta')
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
                            
        $pesertaIds = $peserta_list->pluck('id');
        $query = TaskSubmission::whereIn('participant_id', $pesertaIds)
                            ->with(['task', 'participant']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('participant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('task_type') && $request->task_type != '') {
            $taskType = $request->task_type;
            $query->whereHas('task', function($q) use ($taskType) {
                $q->where('task_type', $taskType);
            });
        }
        
        if ($request->has('status') && $request->status == 'terlambat') {
            $query->select('task_submissions.*')
                  ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                  ->whereColumn('task_submissions.submitted_at', '>', 'tasks.due_date');
        }

        $data['submissions'] = $query->orderBy('task_submissions.submitted_at', 'desc')->get();
        $data['search'] = $request->search;
        $data['task_type_filter'] = $request->task_type;
        $data['status_filter'] = $request->status;
        $data['task_types'] = [
            'individu' => 'Individu',
            'per_sektor' => 'Per Sektor',
            'angkatan' => 'Satu Angkatan'
        ];
                            
        return view('mentor.submissions', $data);
    }
    
    public function mentorSubmissionsDownload(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $peserta_list = User::where('role', 'peserta')
                            ->where('sektor', $user->sektor)
                            ->where('is_approved', true)
                            ->get();
        $pesertaIds = $peserta_list->pluck('id');

        $query = TaskSubmission::whereIn('participant_id', $pesertaIds)
                            ->with(['task', 'participant'])
                            ->whereNotNull('file_url');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('participant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('task_type') && $request->task_type != '') {
            $taskType = $request->task_type;
            $query->whereHas('task', function($q) use ($taskType) {
                $q->where('task_type', $taskType);
            });
        }
        
        if ($request->has('status') && $request->status == 'terlambat') {
            $query->select('task_submissions.*')
                  ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                  ->whereColumn('task_submissions.submitted_at', '>', 'tasks.due_date');
        }

        $submissions = $query->get();
        
        if ($submissions->isEmpty()) {
            return back()->with('error', 'Tidak ada file untuk didownload dengan filter tersebut.');
        }

        $zipFileName = 'submissions_sektor_' . $user->sektor . '_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($submissions as $sub) {
                $path = str_replace(asset('storage/'), '', $sub->file_url);
                $path = urldecode($path);
                $realPath = storage_path('app/public/' . ltrim($path, '/'));
                
                if (file_exists($realPath) && !is_dir($realPath)) {
                    $cleanTask = preg_replace('/[^A-Za-z0-9\-]/', '_', $sub->task->title);
                    $cleanParticipant = preg_replace('/[^A-Za-z0-9\-]/', '_', $sub->participant->name);
                    $originalName = $sub->file_name ?? basename($realPath);
                    
                    $newName = "{$cleanTask}/{$cleanParticipant}/{$originalName}";
                    $zip->addFile($realPath, $newName);
                }
            }
            $zip->close();
            
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }
        
        return back()->with('error', 'Gagal membuat file ZIP.');
    }
    
    public function mentorTasks()
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $data = [];
        $data['tasks'] = Task::where('is_draft', false)
                            ->where(function($query) use ($user) {
                                $query->where('sector', $user->sektor)
                                      ->orWhere('sector', 0)
                                      ->orWhere('task_type', 'angkatan');
                            })
                            ->orderBy('due_date', 'asc')
                            ->get();
                            
        return view('mentor.tasks', $data);
    }
    
    public function mentorTaskStatus($id)
    {
        $user = auth()->user();
        if ($user->role !== 'mentor') abort(403);
        
        $task = Task::findOrFail($id);
        
        $participants = User::where('role', 'peserta')
            ->where('sektor', $user->sektor)
            ->where('is_approved', true)
            ->select('id', 'name', 'nim')
            ->orderBy('name', 'asc')
            ->get();
            
        $submissions = TaskSubmission::where('task_id', $id)
            ->whereIn('participant_id', $participants->pluck('id'))
            ->pluck('participant_id')
            ->toArray();
            
        $submitted = [];
        $not_submitted = [];
        
        foreach ($participants as $p) {
            if (in_array($p->id, $submissions)) {
                $submitted[] = $p;
            } else {
                $not_submitted[] = $p;
            }
        }
        
        return response()->json([
            'submitted' => $submitted,
            'not_submitted' => $not_submitted
        ]);
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

    public function adminSubmissions()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') abort(403);
        
        $submissions = TaskSubmission::with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->paginate(15);
                            
        return view('admin.submissions', compact('submissions'));
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

    public function adminTasks()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $data = [];
        $data['tasks'] = Task::withCount('submissions')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
                            
        return view('admin.tasks', $data);
    }

    public function stakeholderDashboard()
    {
        if (auth()->user()->role !== 'stakeholder') abort(403);
        
        $data = [];
        $data['total_tasks'] = Task::count();
        $data['total_submissions'] = TaskSubmission::count();
        $data['total_permissions'] = \App\Models\ExitPermission::count();
        $data['recent_submissions'] = TaskSubmission::with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->limit(10)
                            ->get();
                            
        return view('stakeholder.dashboard', $data);
    }

    public function stakeholderSubmissions()
    {
        if (auth()->user()->role !== 'stakeholder') abort(403);
        
        $submissions = TaskSubmission::with(['task', 'participant'])
                            ->orderBy('submitted_at', 'desc')
                            ->paginate(15);
                            
        return view('stakeholder.submissions', compact('submissions'));
    }

    public function stakeholderTasks()
    {
        if (auth()->user()->role !== 'stakeholder') abort(403);
        
        $data = [];
        $data['tasks'] = Task::withCount('submissions')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
                            
        return view('stakeholder.tasks', $data);
    }
    public function stakeholderTaskStatus($id)
    {
        $user = auth()->user();
        if ($user->role !== 'stakeholder') abort(403);
        
        $task = Task::findOrFail($id);
        
        $participantsQuery = User::where('role', 'peserta')
            ->where('is_approved', true);
            
        if ($task->task_type === 'per_sektor' && $task->sector != 0) {
            $participantsQuery->where('sektor', $task->sector);
        }
            
        $participants = $participantsQuery->select('id', 'name', 'nim', 'sektor')
            ->orderBy('sektor', 'asc')
            ->orderBy('name', 'asc')
            ->get();
            
        $submissions = TaskSubmission::where('task_id', $id)
            ->whereIn('participant_id', $participants->pluck('id'))
            ->pluck('participant_id')
            ->toArray();
            
        $submitted = [];
        $not_submitted = [];
        
        foreach ($participants as $p) {
            if (in_array($p->id, $submissions)) {
                $submitted[] = $p;
            } else {
                $not_submitted[] = $p;
            }
        }
        
        return response()->json([
            'submitted' => $submitted,
            'not_submitted' => $not_submitted
        ]);
    }
}
