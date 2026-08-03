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
        Schema::create('applicant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('nik', 20);
            $table->string('nama');
            $table->enum('kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->text('domisili')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('agama')->nullable();
            $table->string('status')->nullable();
            $table->string('bpjs')->nullable();
            $table->string('npwp')->nullable();

            $table->string('pendidikan')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('sekolah')->nullable();

            $table->string('cv')->nullable();

            $table->string('foto')->nullable();

            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};
