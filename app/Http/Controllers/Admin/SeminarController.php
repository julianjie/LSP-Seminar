<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class SeminarController extends Controller
{
    /**
     * Display a listing of seminars
     */
    public function index()
    {
        $seminars = Seminar::orderBy('seminar_date', 'desc')->paginate(10);
        return view('admin.seminars.index', compact('seminars'));
    }

    /**
     * Show the form for creating a new seminar
     */
    public function create()
    {
        return view('admin.seminars.create');
    }

    /**
     * Store a newly created seminar in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:seminars,slug',
            'description' => 'required|string',
            'speaker' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'seminar_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'quota' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'registration_deadline' => 'required|date|before_or_equal:seminar_date',
            'status' => 'required|in:draft,published,closed,completed',
        ], [
            'slug.unique' => 'Slug sudah digunakan, pilih judul atau slug lain.',
            'registration_deadline.before_or_equal' => 'Batas pendaftaran harus sebelum atau sama dengan tanggal pelaksanaan seminar.',
            'poster.max' => 'Ukuran poster tidak boleh lebih dari 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('poster');

            // Handle upload poster
            if ($request->hasFile('poster')) {
                $file = $request->file('poster');
                $filename = 'poster_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posters', $filename, 'public');
                $data['poster'] = $path;
            }

            Seminar::create($data);

            DB::commit();

            return redirect()->route('admin.seminars.index')->with('success', 'Seminar berhasil ditambahkan.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified seminar detail
     */
    public function show(Seminar $seminar)
    {
        // Load registrations with their users
        $registrations = $seminar->registrations()->with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.seminars.show', compact('seminar', 'registrations'));
    }

    /**
     * Show the form for editing the specified seminar
     */
    public function edit(Seminar $seminar)
    {
        return view('admin.seminars.edit', compact('seminar'));
    }

    /**
     * Update the specified seminar in storage
     */
    public function update(Request $request, Seminar $seminar)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:seminars,slug,' . $seminar->id,
            'description' => 'required|string',
            'speaker' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'seminar_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'quota' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'registration_deadline' => 'required|date|before_or_equal:seminar_date',
            'status' => 'required|in:draft,published,closed,completed',
        ], [
            'slug.unique' => 'Slug sudah digunakan, pilih judul atau slug lain.',
            'registration_deadline.before_or_equal' => 'Batas pendaftaran harus sebelum atau sama dengan tanggal pelaksanaan seminar.',
            'poster.max' => 'Ukuran poster tidak boleh lebih dari 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('poster');

            // Handle upload poster
            if ($request->hasFile('poster')) {
                // Delete old poster if exists
                if ($seminar->poster && Storage::disk('public')->exists($seminar->poster)) {
                    Storage::disk('public')->delete($seminar->poster);
                }

                $file = $request->file('poster');
                $filename = 'poster_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posters', $filename, 'public');
                $data['poster'] = $path;
            }

            $seminar->update($data);

            DB::commit();

            return redirect()->route('admin.seminars.index')->with('success', 'Seminar berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified seminar from storage
     */
    public function destroy(Seminar $seminar)
    {
        try {
            DB::beginTransaction();

            // Delete poster if exists
            if ($seminar->poster && Storage::disk('public')->exists($seminar->poster)) {
                Storage::disk('public')->delete($seminar->poster);
            }

            $seminar->delete();

            DB::commit();

            return redirect()->route('admin.seminars.index')->with('success', 'Seminar berhasil dihapus.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}
