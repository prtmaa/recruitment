<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Exports\JobApplicationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class JobApplicationController extends Controller
{
    protected array $allowedTransitions = [
        'pending'   => ['review', 'interview'],
        'review'    => ['interview', 'rejected'],
        'interview' => ['accepted', 'rejected'],
        'accepted'  => [],
        'rejected'  => [],
    ];

    protected array $statusLabel = [
        'pending'   => 'Menunggu',
        'review'    => 'Review',
        'interview' => 'Interview',
        'accepted'  => 'Diterima',
        'rejected'  => 'Ditolak',
    ];

    protected array $statusConfig = [
        'pending'   => ['label' => 'Menunggu', 'class' => 'secondary'],
        'review'    => ['label' => 'Review', 'class' => 'info'],
        'interview' => ['label' => 'Interview', 'class' => 'warning'],
        'accepted'  => ['label' => 'Diterima', 'class' => 'success'],
        'rejected'  => ['label' => 'Ditolak', 'class' => 'danger'],
    ];

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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicantProfile', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('tanggal_melamar')->get();
        $groupedByStatus = $applications->groupBy('status');

        return view('admin.seleksi.show', [
            'job'                => $job,
            'applications'       => $applications,
            'groupedByStatus'    => $groupedByStatus,
            'statusConfig'       => $this->statusConfig,
            'allowedTransitions' => $this->allowedTransitions,
        ]);
    }

    /**
     * Render ulang satu item ringkasan (partial ringkasan-item.blade.php)
     * dengan status & data terbaru. Dipakai setelah update status baik
     * single maupun bulk, supaya checkbox, data-tab, dan tombol aksi
     * (termasuk WA di status interview) selalu sesuai kondisi terbaru
     * tanpa perlu reload halaman.
     */
    protected function renderRingkasanItem(JobApplication $application): string
    {
        $canBulk = count($this->allowedTransitions[$application->status] ?? []) > 0;

        return view('admin.seleksi.ringkasan-item', [
            'app'     => $application,
            'job'     => $application->job,
            'profile' => $application->applicantProfile,
            'canBulk' => $canBulk,
        ])->render();
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status'      => 'required|in:pending,review,interview,accepted,rejected',
            'catatan_hrd' => 'nullable|string|max:1000',
        ]);

        $currentStatus = $application->status;
        $newStatus = $request->status;

        if ($newStatus !== $currentStatus) {
            $allowed = $this->allowedTransitions[$currentStatus] ?? [];

            if (!in_array($newStatus, $allowed)) {
                $message = "Status tidak bisa diubah langsung dari '{$this->statusLabel[$currentStatus]}' ke '{$this->statusLabel[$newStatus]}'.";

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
            'status'      => $newStatus,
            'catatan_hrd' => $request->catatan_hrd,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $application->load(['applicantProfile.user', 'job.interviewSchedule']);

            $config = $this->statusConfig[$application->status];
            $badgeHtml = '<span class="badge badge-' . $config['class'] . '">' . $config['label'] . '</span>';

            $aksiHtml = view('admin.seleksi.aksi', [
                'app'     => $application,
                'job'     => $application->job,
                'profile' => $application->applicantProfile,
            ])->render();

            return response()->json([
                'success'        => true,
                'message'        => 'Status lamaran berhasil diperbarui.',
                'app_id'         => $application->id,
                'new_status'     => $application->status,
                'badge_html'     => $badgeHtml,
                'aksi_html'      => $aksiHtml,
                'ringkasan_html' => $this->renderRingkasanItem($application),
            ]);
        }

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }

    public function export(Request $request, Job $job)
    {
        $filters = $request->only(['status', 'tanggal_dari', 'tanggal_sampai']);

        $filename = 'pelamar-' . Str::slug($job->judul) . '-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new JobApplicationExport($job, $filters), $filename);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:job_applications,id',
            'status' => 'required|in:pending,review,interview,accepted,rejected',
        ]);

        $newStatus = $request->status;

        $applications = JobApplication::with(['applicantProfile.user', 'job.interviewSchedule'])
            ->whereIn('id', $request->ids)
            ->get();

        $updated = [];
        $skipped = [];

        foreach ($applications as $application) {
            $currentStatus = $application->status;

            if ($currentStatus === $newStatus) {
                $skipped[] = [
                    'id'     => $application->id,
                    'nama'   => $application->applicantProfile->nama,
                    'alasan' => 'Status sudah ' . $this->statusLabel[$newStatus],
                ];
                continue;
            }

            $allowed = $this->allowedTransitions[$currentStatus] ?? [];

            if (!in_array($newStatus, $allowed)) {
                $skipped[] = [
                    'id'     => $application->id,
                    'nama'   => $application->applicantProfile->nama,
                    'alasan' => "Tidak bisa dari '{$this->statusLabel[$currentStatus]}' ke '{$this->statusLabel[$newStatus]}'",
                ];
                continue;
            }

            $application->update(['status' => $newStatus]);

            $config = $this->statusConfig[$newStatus];
            $badgeHtml = '<span class="badge badge-' . $config['class'] . '">' . $config['label'] . '</span>';

            $aksiHtml = view('admin.seleksi.aksi', [
                'app'     => $application,
                'job'     => $application->job,
                'profile' => $application->applicantProfile,
            ])->render();

            $updated[] = [
                'id'             => $application->id,
                'old_status'     => $currentStatus,
                'new_status'     => $newStatus,
                'badge_html'     => $badgeHtml,
                'aksi_html'      => $aksiHtml,
                'ringkasan_html' => $this->renderRingkasanItem($application),
            ];
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'skipped' => $skipped,
            'message' => count($updated) . ' data berhasil diperbarui'
                . (count($skipped) ? ', ' . count($skipped) . ' data dilewati.' : '.'),
        ]);
    }
}
