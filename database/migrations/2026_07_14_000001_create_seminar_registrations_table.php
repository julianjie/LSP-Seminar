<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seminar_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seminar_id')->constrained('seminars')->onDelete('cascade');
            $table->string('registration_code')->unique();
            $table->enum('registration_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'waiting_verification', 'paid', 'rejected'])->default('unpaid');
            $table->string('payment_proof')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            // Mencegah pendaftaran ganda oleh user yang sama untuk seminar yang sama
            $table->unique(['user_id', 'seminar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar_registrations');
    }
};
