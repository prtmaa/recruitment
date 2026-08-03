<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperiences extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'mulai_kerja' => 'date',
        'berhenti_kerja' => 'date',
    ];

    public function applicantProfile()
    {
        return $this->belongsTo(ApplicantProfile::class);
    }
}
