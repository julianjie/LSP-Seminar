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
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('speaker');
            $table->string('location');
            $table->date('seminar_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('quota');
            $table->unsignedInteger('price')->default(0);
            $table->string('poster')->nullable();
            $table->date('registration_deadline');
            $table->enum('status', ['draft', 'published', 'closed', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
