<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function interviewSchedule()
    {
        return $this->hasOne(InterviewSchedule::class);
    }

    // public function applicants()
    // {
    //     return $this->belongsToMany(
    //         ApplicantProfile::class,
    //         'job_applications'
    //     )->withPivot('status', 'catatan_hrd', 'tanggal_melamar')->withTimestamps();
    // }
}
