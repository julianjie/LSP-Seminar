<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard statistics and charts
     */
    public function index()
    {
        // Statistik
        $totalParticipants = User::where('role', 'participant')->count();
        $pendingParticipants = User::where('role', 'participant')->where('account_status', 'pending')->count();
        $totalSeminars = Seminar::count();
        $activeSeminars = Seminar::published()->count();
        $totalRegistrations = SeminarRegistration::count();
        $pendingPayments = SeminarRegistration::where('payment_status', 'waiting_verification')->count();

        // Data list
        $recentRegistrations = SeminarRegistration::with(['user', 'seminar'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingSeminars = Seminar::where('seminar_date', '>=', now()->toDateString())
            ->orderBy('seminar_date', 'asc')
            ->take(5)
            ->get();

        // Data Grafik Pendaftaran (10 hari terakhir)
        $registrationsChart = SeminarRegistration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(10))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];

        foreach ($registrationsChart as $data) {
            $chartLabels[] = date('d M', strtotime($data->date));
            $chartValues[] = $data->total;
        }

        return view('admin.dashboard', compact(
            'totalParticipants',
            'pendingParticipants',
            'totalSeminars',
            'activeSeminars',
            'totalRegistrations',
            'pendingPayments',
            'recentRegistrations',
            'upcomingSeminars',
            'chartLabels',
            'chartValues'
        ));
    }
}
