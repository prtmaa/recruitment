<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $job = Job::where('is_active', 1)
            ->whereDate('tanggal_tutup', '>=', now())
            ->get();

        $setting = Setting::first();

        return view('dashboard.index', compact('job', 'setting'));
    }
}
