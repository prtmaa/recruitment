<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;

        $lamaran = collect();

        if ($profile) {
            $lamaran = JobApplication::with(['job', 'histories.changedBy'])
                ->where('applicant_profile_id', $profile->id)
                ->latest('tanggal_melamar')
                ->get();
        }

        return view('pelamar.lamaran.index', compact('lamaran'));
    }
}
