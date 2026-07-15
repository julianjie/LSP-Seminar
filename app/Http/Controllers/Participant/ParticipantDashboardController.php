<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantDashboardController extends Controller
{
    /**
     * Show the participant dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil pengumuman aktif untuk peserta
        $announcements = Announcement::activeAndPublished()
            ->forRole('participant')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // 2. Ambil seminar yang sudah didaftarkan oleh peserta
        $myRegistrations = SeminarRegistration::with('seminar')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Ambil seminar yang tersedia untuk didaftar (status: published, belum lewat deadline, belum didaftar)
        $registeredSeminarIds = $myRegistrations->pluck('seminar_id')->toArray();
        
        $availableSeminars = Seminar::published()
            ->where('registration_deadline', '>=', now()->toDateString())
            ->whereNotIn('id', $registeredSeminarIds)
            ->orderBy('seminar_date', 'asc')
            ->get();

        return view('participant.dashboard', compact(
            'user',
            'announcements',
            'myRegistrations',
            'availableSeminars'
        ));
    }
}
