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
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_profile_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('perusahaan');
            $table->string('posisi');
            $table->string('kota');
            $table->date('mulai_kerja');
            $table->date('berhenti_kerja')->nullable();
            $table->text('tanggung_jawab')->nullable();
            $table->enum('masih_bekerja', ['1', '0'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
