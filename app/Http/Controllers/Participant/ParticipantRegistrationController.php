<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class ParticipantRegistrationController extends Controller
{
    /**
     * Handle seminar registration by participant
     */
    public function register(Request $request, Seminar $seminar)
    {
        $user = Auth::user();

        // 1. Validasi status seminar harus published
        if ($seminar->status !== 'published') {
            return back()->withErrors(['error' => 'Seminar ini tidak tersedia untuk pendaftaran.']);
        }

        // 2. Validasi pendaftaran ganda
        $exists = SeminarRegistration::where('user_id', $user->id)
            ->where('seminar_id', $seminar->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Anda sudah terdaftar dalam seminar ini.']);
        }

        // 3. Validasi batas waktu pendaftaran (deadline)
        if (now()->toDateString() > $seminar->registration_deadline->toDateString()) {
            return back()->withErrors(['error' => 'Batas waktu pendaftaran untuk seminar ini telah ditutup.']);
        }

        // 4. Validasi kuota penuh
        if (!$seminar->hasAvailableQuota()) {
            return back()->withErrors(['error' => 'Pendaftaran gagal karena kuota seminar sudah penuh.']);
        }

        try {
            DB::beginTransaction();

            $regCode = 'REG-' . strtoupper(date('ymd')) . '-' . strtoupper(Str::random(4));

            // Jika seminar gratis, status pembayaran langsung paid
            $paymentStatus = 'unpaid';
            $paymentDate = null;
            $adminNote = null;

            if ($seminar->price == 0) {
                $paymentStatus = 'paid';
                $paymentDate = now();
                $adminNote = 'Seminar gratis, pembayaran terverifikasi otomatis.';
            }

            SeminarRegistration::create([
                'user_id' => $user->id,
                'seminar_id' => $seminar->id,
                'registration_code' => $regCode,
                'registration_status' => 'pending',
                'payment_status' => $paymentStatus,
                'payment_proof' => null,
                'payment_date' => $paymentDate,
                'admin_note' => $adminNote,
            ]);

            DB::commit();

            return redirect()->route('participant.dashboard')->with('success', 'Pendaftaran seminar ' . $seminar->title . ' berhasil! Silakan ikuti petunjuk berikutnya untuk proses pembayaran.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal melakukan pendaftaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Show registration details (payment proof upload page)
     */
    public function show(SeminarRegistration $registration)
    {
        // Pastikan hanya pemilik pendaftaran yang dapat membukanya
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke detail pendaftaran ini.');
        }

        return view('participant.registrations.show', compact('registration'));
    }

    /**
     * Upload proof of payment
     */
    public function uploadPayment(Request $request, SeminarRegistration $registration)
    {
        // Pastikan pemilik pendaftaran yang mengunggah
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Validasi upload bukti pembayaran
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'payment_proof.required' => 'File bukti pembayaran wajib dipilih.',
            'payment_proof.mimes' => 'Format file harus berupa JPG, JPEG, PNG, atau PDF.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran tidak boleh melebihi 2MB.',
        ]);

        try {
            DB::beginTransaction();

            // Jika mengunggah ulang bukti pembayaran yang ditolak, hapus file lama
            if ($registration->payment_proof && Storage::disk('public')->exists($registration->payment_proof)) {
                Storage::disk('public')->delete($registration->payment_proof);
            }

            $file = $request->file('payment_proof');
            $filename = 'proof_' . $registration->registration_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payments', $filename, 'public');

            $registration->payment_proof = $path;
            $registration->payment_status = 'waiting_verification';
            $registration->registration_status = 'pending'; // Reset pendaftaran status ke pending jika direject sebelumnya
            $registration->payment_date = now();
            $registration->save();

            DB::commit();

            return redirect()->route('participant.registrations.show', $registration->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi admin.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage()]);
        }
    }
}
