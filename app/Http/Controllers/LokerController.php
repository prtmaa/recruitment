<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Job;
use App\Models\Setting;
use Illuminate\Http\Request;

class LokerController extends Controller
{
    public function index()
    {
        $job = Job::where('is_active', 1)
            ->whereDate('tanggal_tutup', '>=', now())
            ->get();

        $setting = Setting::first();

        $lamaranAktif = null;
        if (auth()->check() && auth()->user()->profile) {
            $lamaranAktif = JobApplication::where('applicant_profile_id', auth()->user()->profile->id)
                ->where('status', '!=', 'rejected')
                ->whereHas('job', function ($q) {
                    $q->where('is_active', 1)
                        ->whereDate('tanggal_tutup', '>=', now());
                })
                ->with('job')
                ->latest()
                ->first();
        }

        return view('pelamar.loker.index', compact('job', 'setting', 'lamaranAktif'));
    }

    public function batalLamar(JobApplication $application)
    {
        $profile = auth()->user()->profile;

        if (!$profile || $application->applicant_profile_id !== $profile->id) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return back()->with('error', 'Lamaran tidak bisa dibatalkan karena sudah diproses.');
        }

        $application->delete();

        return back()->with('success', 'Lamaran berhasil dibatalkan.');
    }
}
