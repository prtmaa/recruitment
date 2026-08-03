<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // ringkasan lamaran
        $totalLamaran = 0;
        $ringkasanStatus = [
            'pending' => 0,
            'review' => 0,
            'interview' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];
        $lamaranTerbaru = collect();
        $interviewMendatang = collect();

        if ($profile) {
            $totalLamaran = JobApplication::where('applicant_profile_id', $profile->id)->count();

            $ringkasanStatus = JobApplication::where('applicant_profile_id', $profile->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray() + $ringkasanStatus;

            $lamaranTerbaru = JobApplication::with('job')
                ->where('applicant_profile_id', $profile->id)
                ->latest('tanggal_melamar')
                ->take(5)
                ->get();

            // lamaran berstatus interview + job-nya sudah punya jadwal
            $interviewMendatang = JobApplication::with(['job.interviewSchedule'])
                ->where('applicant_profile_id', $profile->id)
                ->where('status', 'interview')
                ->whereHas('job.interviewSchedule', function ($q) {
                    $q->whereDate('tanggal_interview', '>=', now()->toDateString());
                })
                ->get();
        }

        return view('pelamar.home.index', compact(
            'profile',
            'totalLamaran',
            'ringkasanStatus',
            'lamaranTerbaru',
            'interviewMendatang'
        ));
    }
}
