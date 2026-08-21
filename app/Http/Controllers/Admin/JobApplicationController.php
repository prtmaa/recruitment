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
            'applicantProfile.applications.job',
            'applicantProfile.province',
            'applicantProfile.city',
            'applicantProfile.district',
            'applicantProfile.village',
        ]);

        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicantProfile', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('tanggal_melamar')->get();

        // Kelompokkan pelamar per status untuk tampilan ringkasan
        $groupedByStatus = $applications->groupBy('status');

        return view('admin.seleksi.show', compact('job', 'applications', 'groupedByStatus'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,review,interview,accepted,rejected',
            'catatan_hrd' => 'nullable|string|max:1000',
        ]);

        $allowedTransitions = [
            'pending'   => ['review', 'interview'],
            'review'    => ['interview', 'rejected'],
            'interview' => ['accepted', 'rejected'],
            'accepted'  => [],
            'rejected'  => [],
        ];

        $currentStatus = $application->status;
        $newStatus = $request->status;

        // Kalau status tidak berubah, izinkan (misal cuma update catatan)
        if ($newStatus !== $currentStatus) {
            $allowed = $allowedTransitions[$currentStatus] ?? [];

            if (!in_array($newStatus, $allowed)) {
                $statusLabel = [
                    'pending'   => 'Menunggu',
                    'review'    => 'Review',
                    'interview' => 'Interview',
                    'accepted'  => 'Diterima',
                    'rejected'  => 'Ditolak',
                ];

                $message = "Status tidak bisa diubah langsung dari '{$statusLabel[$currentStatus]}' ke '{$statusLabel[$newStatus]}'.";

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                    ], 422);
                }

                return back()->withErrors(['status' => $message]);
            }
        }

        $application->update([
            'status' => $newStatus,
            'catatan_hrd' => $request->catatan_hrd,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $application->load(['applicantProfile.user', 'job.interviewSchedule']);

            $statusConfig = [
                'pending'   => ['label' => 'Menunggu', 'class' => 'secondary'],
                'review'    => ['label' => 'Review', 'class' => 'info'],
                'interview' => ['label' => 'Interview', 'class' => 'warning'],
                'accepted'  => ['label' => 'Diterima', 'class' => 'success'],
                'rejected'  => ['label' => 'Ditolak', 'class' => 'danger'],
            ];
            $config = $statusConfig[$application->status];

            $badgeHtml = '<span class="badge badge-' . $config['class'] . '">' . $config['label'] . '</span>';

            $aksiHtml = view('admin.seleksi.aksi', [
                'app'     => $application,
                'job'     => $application->job,
                'profile' => $application->applicantProfile,
            ])->render();

            return response()->json([
                'success'    => true,
                'message'    => 'Status lamaran berhasil diperbarui.',
                'app_id'     => $application->id,
                'new_status' => $application->status,
                'badge_html' => $badgeHtml,
                'aksi_html'  => $aksiHtml,
            ]);
        }

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
