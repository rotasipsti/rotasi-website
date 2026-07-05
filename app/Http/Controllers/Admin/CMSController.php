<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timeline;
use App\Models\Stakeholder;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\Download;
use App\Models\PageContent;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    public function index()
    {
        $timelines = Timeline::orderBy('order')->get();
        $stakeholders = Stakeholder::orderBy('order')->get();
        $divisions = Division::with('members')->orderBy('order')->get();
        $galleries = Gallery::orderBy('order')->get();
        $testimonials = Testimonial::orderBy('order')->get();
        $pageContents = PageContent::all()->keyBy('key');

        return view('admin.cms.index', compact(
            'timelines', 'stakeholders', 'divisions', 'galleries', 
            'testimonials', 'pageContents'
        ));
    }

    public function downloads()
    {
        $downloads = Download::orderBy('order')->get();
        return view('admin.cms.downloads', compact('downloads'));
    }

    // Helper method to handle file uploads vs URLs
    private function handleImageUpload(Request $request, $model, $fieldName = 'image')
    {
        if ($request->input($fieldName . '_type') === 'file' && $request->hasFile($fieldName . '_file')) {
            $file = $request->file($fieldName . '_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('cms', $fileName, 'public');
            
            $model->{$fieldName} = asset('storage/' . $path);
            $model->{$fieldName . '_type'} = 'file';
        } elseif ($request->input($fieldName . '_type') === 'url' && $request->filled($fieldName . '_url')) {
            $model->{$fieldName} = $request->input($fieldName . '_url');
            $model->{$fieldName . '_type'} = 'url';
        }
    }

    // Timeline Methods
    public function storeTimeline(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'location' => 'required',
            'duration' => 'required',
            'desc' => 'required',
            'activities' => 'required', // Will be sent as comma-separated or newline
            'order' => 'integer'
        ]);

        $activities = array_filter(array_map('trim', explode("\n", $validated['activities'])));
        $validated['activities'] = $activities;

        Timeline::create($validated);
        return redirect()->back()->with('success', 'Timeline berhasil ditambahkan.');
    }

    public function destroyTimeline(Timeline $timeline)
    {
        $timeline->delete();
        return redirect()->back()->with('success', 'Timeline dihapus.');
    }

    // Stakeholder Methods
    public function storeStakeholder(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'image_type' => 'required|in:url,file',
            'image_url' => 'required_if:image_type,url',
            'image_file' => 'required_if:image_type,file|image|max:51200',
        ]);

        $stakeholder = new Stakeholder();
        $stakeholder->name = $request->name;
        $stakeholder->role = $request->role;
        $stakeholder->instagram = $request->instagram;
        $stakeholder->email = $request->email;
        $stakeholder->desc = $request->desc;
        
        $this->handleImageUpload($request, $stakeholder);
        $stakeholder->save();

        return redirect()->back()->with('success', 'Stakeholder berhasil ditambahkan.');
    }

    public function destroyStakeholder(Stakeholder $stakeholder)
    {
        $stakeholder->delete();
        return redirect()->back()->with('success', 'Stakeholder dihapus.');
    }

    // Gallery Methods
    public function storeGallery(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'caption' => 'required',
            'image_type' => 'required|in:url,file',
            'image_url' => 'required_if:image_type,url',
            'image_file' => 'required_if:image_type,file|image|max:51200',
        ]);

        $gallery = new Gallery();
        $gallery->year = $request->year;
        $gallery->caption = $request->caption;
        
        $this->handleImageUpload($request, $gallery);
        $gallery->save();

        return redirect()->back()->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function destroyGallery(Gallery $gallery)
    {
        $gallery->delete();
        return redirect()->back()->with('success', 'Galeri dihapus.');
    }

    // Division Methods
    public function storeDivision(Request $request)
    {
        $division = new Division();
        $division->name = $request->name;
        $division->desc = $request->desc;
        $division->order = $request->order ?? 0;
        $division->save();

        return redirect()->back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function destroyDivision(Division $division)
    {
        $division->delete();
        return redirect()->back()->with('success', 'Divisi dihapus.');
    }

    public function storeDivisionMember(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'image_type' => 'required|in:url,file',
            'image_url' => 'required_if:image_type,url',
            'image_file' => 'required_if:image_type,file|image|max:51200',
        ]);

        $member = new DivisionMember();
        $member->division_id = $division->id;
        $member->name = $request->name;
        $member->role = $request->role;
        $member->instagram = $request->instagram;
        
        $this->handleImageUpload($request, $member);
        $member->save();

        return redirect()->back()->with('success', 'Anggota divisi ditambahkan.');
    }

    public function destroyDivisionMember(DivisionMember $member)
    {
        $member->delete();
        return redirect()->back()->with('success', 'Anggota divisi dihapus.');
    }

    // Testimonial Methods
    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'message' => 'required',
            'image_type' => 'required|in:url,file',
            'image_url' => 'required_if:image_type,url',
            'image_file' => 'required_if:image_type,file|image|max:51200',
        ]);

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->role = $request->role;
        $testimonial->message = $request->message;
        $testimonial->order = $request->order ?? 0;
        
        $this->handleImageUpload($request, $testimonial);
        $testimonial->save();

        return redirect()->back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->back()->with('success', 'Testimoni dihapus.');
    }

    // Download Methods
    public function storeDownload(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file_url' => 'required|url',
        ]);

        $download = new Download();
        $download->title = $request->title;
        $download->desc = $request->desc;
        $download->url = $request->file_url;
        $download->type = 'url';
        $download->order = $request->order ?? 0;
        $download->save();

        return redirect()->back()->with('success', 'Dokumen unduhan berhasil ditambahkan.');
    }

    public function destroyDownload(Download $download)
    {
        $download->delete();
        return redirect()->back()->with('success', 'Dokumen unduhan dihapus.');
    }

    // Page Content Method
    public function updatePageContent(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            if ($value !== null) {
                PageContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        return redirect()->back()->with('success', 'Pengaturan teks halaman berhasil disimpan.');
    }
}
