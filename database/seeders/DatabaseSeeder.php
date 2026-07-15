<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'account_status' => 'approved',
            'phone' => '081234567890',
            'institution' => 'LSP LSP-UMDP',
        ]);

        // 2. Seed Participants
        $approvedParticipant = User::create([
            'name' => 'Peserta Approved',
            'email' => 'peserta@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'participant',
            'account_status' => 'approved',
            'phone' => '089876543210',
            'institution' => 'Universitas Multi Data Palembang',
        ]);

        $pendingParticipant = User::create([
            'name' => 'Peserta Pending',
            'email' => 'pending@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'participant',
            'account_status' => 'pending',
            'phone' => '082211334455',
            'institution' => 'Politeknik Negeri Sriwijaya',
        ]);

        $rejectedParticipant = User::create([
            'name' => 'Peserta Rejected',
            'email' => 'rejected@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'participant',
            'account_status' => 'rejected',
            'phone' => '085544332211',
            'institution' => 'Universitas Sriwijaya',
        ]);

        // 3. Seed Seminars
        $seminar1 = Seminar::create([
            'title' => 'Belajar Laravel 11 untuk Pemula',
            'slug' => 'belajar-laravel-11-untuk-pemula',
            'description' => 'Seminar ini membahas dasar-dasar Laravel 11, struktur folder baru, routing, controller, view dengan Blade, dan integrasi database secara sederhana dan terstruktur untuk pemula.',
            'speaker' => 'Taylor Otwell',
            'location' => 'Auditorium Lantai 5 UMDP',
            'seminar_date' => now()->addDays(10),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'quota' => 50,
            'price' => 0, // Free
            'poster' => null,
            'registration_deadline' => now()->addDays(7),
            'status' => 'published',
        ]);

        $seminar2 = Seminar::create([
            'title' => 'Membangun Aplikasi Fullstack Premium dengan Bootstrap 5',
            'slug' => 'membangun-aplikasi-fullstack-premium-dengan-bootstrap-5',
            'description' => 'Dalam seminar eksklusif ini, Anda akan mempelajari cara merancang antarmuka web modern yang memiliki nilai jual tinggi menggunakan Bootstrap 5, dipadukan dengan optimalisasi UX dan micro-animations.',
            'speaker' => 'Bootstrap Wizard',
            'location' => 'Lab Komputer Rekayasa Perangkat Lunak',
            'seminar_date' => now()->addDays(5),
            'start_time' => '13:00:00',
            'end_time' => '16:00:00',
            'quota' => 5, // Quota kecil agar mudah menguji batas kuota
            'price' => 100000,
            'poster' => null,
            'registration_deadline' => now()->addDays(3),
            'status' => 'published',
        ]);

        $seminar3 = Seminar::create([
            'title' => 'Keamanan Web & Pentesting Modern',
            'slug' => 'keamanan-web-dan-pentesting-modern',
            'description' => 'Seminar ini mengulas celah keamanan web terpopuler seperti SQL Injection, XSS, CSRF, serta cara melakukan pertahanan (hardening) pada kode pemrograman web Anda.',
            'speaker' => 'Certified Ethical Hacker',
            'location' => 'Aula Gedung B',
            'seminar_date' => now()->addDays(1),
            'start_time' => '10:00:00',
            'end_time' => '15:00:00',
            'quota' => 30,
            'price' => 150000,
            'poster' => null,
            'registration_deadline' => now()->subDay(), // Batas pendaftaran terlampaui (kemarin)
            'status' => 'published', // Published tapi deadline sudah terlewati
        ]);

        $seminar4 = Seminar::create([
            'title' => 'Web Development Masterclass (Draft)',
            'slug' => 'web-development-masterclass-draft',
            'description' => 'Seminar persiapan sertifikasi pengembang web yang memandu asesi dalam menguasai unit-unit kompetensi inti.',
            'speaker' => 'Asesor LSP',
            'location' => 'Online via Zoom',
            'seminar_date' => now()->addDays(15),
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'quota' => 100,
            'price' => 50000,
            'poster' => null,
            'registration_deadline' => now()->addDays(12),
            'status' => 'draft', // Draft, tidak boleh tampil di peserta
        ]);

        // 4. Seed Registrations
        // Pendaftaran oleh approvedParticipant untuk Seminar 1 (Free, otomatis paid, pending approval pendaftaran)
        SeminarRegistration::create([
            'user_id' => $approvedParticipant->id,
            'seminar_id' => $seminar1->id,
            'registration_code' => 'REG-' . strtoupper(Str::random(8)),
            'registration_status' => 'pending',
            'payment_status' => 'paid', // Gratis otomatis paid
            'payment_proof' => null,
            'payment_date' => now(),
            'admin_note' => 'Seminar gratis, pendaftaran menunggu verifikasi kehadiran/admin.',
        ]);

        // Pendaftaran oleh approvedParticipant untuk Seminar 2 (Berbayar, unpaid)
        SeminarRegistration::create([
            'user_id' => $approvedParticipant->id,
            'seminar_id' => $seminar2->id,
            'registration_code' => 'REG-' . strtoupper(Str::random(8)),
            'registration_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_proof' => null,
            'payment_date' => null,
            'admin_note' => null,
        ]);

        // 5. Seed Announcements
        Announcement::create([
            'title' => 'Selamat Datang di Portal Sistem Pendaftaran Seminar',
            'content' => 'Selamat datang para calon peserta seminar sertifikasi LSP. Di portal ini, Anda dapat mendaftar dan mengikuti berbagai kegiatan seminar berkualitas untuk menunjang kompetensi Anda.',
            'image' => null,
            'target' => 'all',
            'is_active' => true,
            'published_at' => now(),
            'created_by' => $admin->id,
        ]);

        Announcement::create([
            'title' => 'Alur Pembayaran & Verifikasi Pendaftaran',
            'content' => 'Untuk peserta yang mendaftar seminar berbayar, silakan mengunggah bukti transfer yang valid dalam format JPG/PNG/PDF (maksimal 2MB). Setelah diunggah, tunggu admin melakukan verifikasi pembayaran dalam waktu 1x24 jam.',
            'image' => null,
            'target' => 'participants',
            'is_active' => true,
            'published_at' => now(),
            'created_by' => $admin->id,
        ]);

        Announcement::create([
            'title' => 'Pengumuman Penting Khusus Administrator',
            'content' => 'Guna menjaga kelancaran sertifikasi LSP, harap memeriksa dashboard verifikasi akun peserta secara berkala dan memproses bukti pembayaran pendaftaran seminar.',
            'image' => null,
            'target' => 'admins',
            'is_active' => true,
            'published_at' => now(),
            'created_by' => $admin->id,
        ]);
    }
}
