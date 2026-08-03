<?php

namespace App\Observers;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobApplicationObserver
{
    public function created(JobApplication $application)
    {
        $application->histories()->create([
            'status_lama' => null,
            'status_baru' => $application->status,
            'changed_by' => Auth::id(),
            'keterangan' => 'Lamaran diajukan.',
        ]);
    }

    public function updated(JobApplication $application)
    {
        if ($application->isDirty('status')) {
            $application->histories()->create([
                'status_lama' => $application->getOriginal('status'),
                'status_baru' => $application->status,
                'changed_by' => Auth::id(),
                'keterangan' => $application->catatan_hrd,
            ]);
        }
    }
}
