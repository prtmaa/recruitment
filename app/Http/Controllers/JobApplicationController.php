<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Proses melamar pekerjaan.
     */
    public function apply(Job $job)
    {
        $user = Auth::user();

        // pastikan sudah login
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melamar.');
        }

        // pastikan role pelamar
        if ($user->role !== 'pelamar') {
            return back()->with('error', 'Hanya akun pelamar yang dapat melamar pekerjaan.');
        }

        // pastikan profil sudah lengkap
        $profile = $user->profile;

        if (!$profile || !$profile->is_complete) {
            return redirect()->route('datadiri.index')
                ->with('error', 'Lengkapi profil Anda terlebih dahulu sebelum melamar.');
        }

        $adaLamaranAktif = JobApplication::where('applicant_profile_id', $profile->id)
            ->where('status', '!=', 'rejected')
            ->whereHas('job', function ($q) {
                $q->where('is_active', 1)
                    ->whereDate('tanggal_tutup', '>=', now());
            })
            ->exists();

        if ($adaLamaranAktif) {
            return back()->with('error', 'Kamu masih memiliki lamaran aktif. Hanya bisa melamar 1 lowongan dalam satu waktu.');
        }

        // cek apakah lowongan masih aktif & belum tutup
        if (!$job->is_active || $job->tanggal_tutup < now()->toDateString()) {
            return back()->with('error', 'Maaf, lowongan ini sudah ditutup.');
        }

        // cek apakah sudah pernah melamar
        $sudahMelamar = JobApplication::where('job_id', $job->id)
            ->where('applicant_profile_id', $profile->id)
            ->exists();

        if ($sudahMelamar) {
            return back()->with('error', 'Anda sudah melamar pada lowongan ini.');
        }

        // simpan lamaran
        JobApplication::create([
            'job_id' => $job->id,
            'applicant_profile_id' => $profile->id,
            'status' => 'pending',
            'tanggal_melamar' => now(),
        ]);

        return back()->with('success', 'Lamaran Anda berhasil dikirim untuk posisi "' . $job->judul . '".');
    }

    /**
     * Daftar lamaran milik user yang sedang login.
     */
    public function myApplications()
    {
        $profile = Auth::user()->profile;

        $applications = JobApplication::with('job')
            ->where('applicant_profile_id', $profile->id)
            ->latest('tanggal_melamar')
            ->get();

        return view('applicant.applications', compact('applications'));
    }
}
