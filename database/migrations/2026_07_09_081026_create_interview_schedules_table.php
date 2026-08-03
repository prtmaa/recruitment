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
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('tanggal_interview');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->string('tempat');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique('job_id'); // 1 job cuma 1 jadwal interview
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};
