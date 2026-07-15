<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RegistrationAdminController extends Controller
{
    /**
     * Display a listing of registrations
     */
    public function index(Request $request)
    {
        $query = SeminarRegistration::with(['user', 'seminar']);

        // Filter berdasarkan status pembayaran
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan status pendaftaran
        if ($request->has('registration_status') && $request->registration_status !== '') {
            $query->where('registration_status', $request->registration_status);
        }

        // Pencarian (Nama Peserta atau Kode Pendaftaran)
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.registrations.index', compact('registrations'));
    }

    /**
     * Verify payment and registration status via AJAX
     */
    public function verifyPayment(Request $request, SeminarRegistration $registration)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($request->action === 'reject' && empty($request->admin_note)) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan penolakan wajib diisi jika pembayaran ditolak.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $seminar = $registration->seminar;

            if ($request->action === 'approve') {
                // Pastikan kuota masih tersedia
                if (!$seminar->hasAvailableQuota() && $registration->registration_status !== 'approved') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pendaftaran tidak dapat disetujui karena kuota seminar "' . $seminar->title . '" sudah penuh.'
                    ], 400);
                }

                $registration->payment_status = 'paid';
                $registration->registration_status = 'approved';
                $registration->admin_note = $request->admin_note ?? 'Pembayaran telah disetujui oleh admin.';
            } else {
                $registration->payment_status = 'rejected';
                $registration->registration_status = 'rejected';
                $registration->admin_note = $request->admin_note;
            }

            $registration->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran ' . $registration->registration_code . ' berhasil di-' . ($request->action === 'approve' ? 'setujui' : 'tolak') . '.',
                'payment_status' => $registration->payment_status,
                'registration_status' => $registration->registration_status,
                'admin_note' => $registration->admin_note,
                'remaining_slots' => $seminar->remainingSlots()
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
