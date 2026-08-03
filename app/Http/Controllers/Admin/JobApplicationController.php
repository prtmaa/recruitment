<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $jobs = Job::withCount([
            'applications',
            'applications as pending_count' => fn($q) => $q->where('status', 'pending'),
            'applications as review_count' => fn($q) => $q->where('status', 'review'),
            'applications as interview_count' => fn($q) => $q->where('status', 'interview'),
            'applications as accepted_count' => fn($q) => $q->where('status', 'accepted'),
            'applications as rejected_count' => fn($q) => $q->where('status', 'rejected'),
        ])->latest()->get();

        return view('admin.seleksi.index', compact('jobs'));
    }

    public function show(Request $request, Job $job)
    {
        $query = $job->applications()->with([
            'applicantProfile.user',
            'applicantProfile.workExperiences' => fn($q) => $q->orderByDesc('mulai_kerja'),
            'applicantProfile.applications.job', // riwayat lamaran ke job lain
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicantProfile', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('tanggal_melamar')->paginate(15)->withQueryString();

        return view('admin.seleksi.show', compact('job', 'applications'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,review,interview,accepted,rejected',
            'catatan_hrd' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $request->status,
            'catatan_hrd' => $request->catatan_hrd,
        ]);

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
