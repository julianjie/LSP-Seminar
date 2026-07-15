<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountVerificationController extends Controller
{
    /**
     * Display a listing of participant accounts
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'participant');

        // Filter berdasarkan status
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('account_status', $request->status);
        }

        // Urutkan berdasarkan tanggal pendaftaran terbaru
        $participants = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.accounts.index', compact('participants'));
    }

    /**
     * Update the participant account status via AJAX
     */
    public function updateStatus(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        try {
            DB::beginTransaction();

            // Hanya perbarui jika pengguna adalah peserta (bukan admin)
            if ($user->role !== 'participant') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya akun peserta yang dapat diverifikasi.'
                ], 400);
            }

            $user->account_status = $request->status;
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status akun ' . $user->name . ' berhasil diubah menjadi ' . strtoupper($request->status) . '.',
                'status' => $user->account_status
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status akun: ' . $e->getMessage()
            ], 500);
        }
    }
}
