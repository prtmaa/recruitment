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
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->string('domisili_provinsi', 2)->nullable()->after('domisili');
            $table->string('domisili_kabupaten', 4)->nullable()->after('domisili_provinsi');
            $table->string('domisili_kecamatan', 7)->nullable()->after('domisili_kabupaten');
            $table->string('domisili_desa', 10)->nullable()->after('domisili_kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn('domisili_provinsi');
            $table->dropColumn('domisili_kabupaten');
            $table->dropColumn('domisili_kecamatan');
            $table->dropColumn('domisili_desa');
        });
    }
};
