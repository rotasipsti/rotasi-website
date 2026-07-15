<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:upload,link',
            'image_file' => 'nullable|required_if:type,upload|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|required_if:type,link|url',
            'action_url' => 'nullable|url',
            'target_role' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['image_file']);
        $data['is_active'] = $request->has('is_active');

        if ($request->type === 'upload' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('banners', 'public');
            $data['image_path'] = $path;
            $data['image_url'] = null; // Clear URL if upload
        } elseif ($request->type === 'link') {
            $data['image_path'] = null; // Clear path if link
        }

        Banner::create($data);

        return redirect()->route('admin.cms.banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:upload,link',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|required_if:type,link|url',
            'action_url' => 'nullable|url',
            'target_role' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['image_file']);
        $data['is_active'] = $request->has('is_active');

        if ($request->type === 'upload') {
            if ($request->hasFile('image_file')) {
                // Delete old file if exists
                if ($banner->image_path) {
                    Storage::disk('public')->delete($banner->image_path);
                }
                $path = $request->file('image_file')->store('banners', 'public');
                $data['image_path'] = $path;
            } else {
                $data['image_path'] = $banner->image_path; // Keep old image if no new upload
            }
            $data['image_url'] = null;
        } elseif ($request->type === 'link') {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = null;
        }

        $banner->update($data);

        return redirect()->route('admin.cms.banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return redirect()->route('admin.cms.banners.index')->with('success', 'Banner berhasil dihapus.');
    }
}
