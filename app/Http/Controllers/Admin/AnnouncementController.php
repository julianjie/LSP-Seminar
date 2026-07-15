<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index()
    {
        $announcements = Announcement::with('creator')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'target' => 'required|in:all,participants,admins',
            'is_active' => 'required|boolean',
            'published_at' => 'required|date',
        ], [
            'image.max' => 'Ukuran gambar pengumuman tidak boleh lebih dari 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('image');
            $data['created_by'] = Auth::id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = 'announcement_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('announcements', $filename, 'public');
                $data['image'] = $path;
            }

            Announcement::create($data);

            DB::commit();

            return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat pengumuman: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'target' => 'required|in:all,participants,admins',
            'is_active' => 'required|boolean',
            'published_at' => 'required|date',
        ], [
            'image.max' => 'Ukuran gambar pengumuman tidak boleh lebih dari 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('image');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
                    Storage::disk('public')->delete($announcement->image);
                }

                $file = $request->file('image');
                $filename = 'announcement_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('announcements', $filename, 'public');
                $data['image'] = $path;
            }

            $announcement->update($data);

            DB::commit();

            return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui pengumuman: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified announcement from storage
     */
    public function destroy(Announcement $announcement)
    {
        try {
            DB::beginTransaction();

            // Delete image if exists
            if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }

            $announcement->delete();

            DB::commit();

            return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus pengumuman: ' . $e->getMessage()]);
        }
    }
}
