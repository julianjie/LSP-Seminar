<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show landing page
     */
    public function welcome()
    {
        // Tampilkan 3 seminar terbaru dengan status published
        $latestSeminars = Seminar::published()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('latestSeminars'));
    }

    /**
     * Show detail of a seminar for public
     */
    public function seminarDetail($slug)
    {
        $seminar = Seminar::where('slug', $slug)
            ->published()
            ->firstOrFail();

        return view('seminar_detail', compact('seminar'));
    }

    /**
     * Show lists of all published seminars
     */
    public function allSeminars()
    {
        $seminars = Seminar::published()
            ->orderBy('seminar_date', 'asc')
            ->paginate(6);

        return view('all_seminars', compact('seminars'));
    }
}
