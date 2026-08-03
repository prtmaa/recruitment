<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_melamar' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function applicantProfile()
    {
        return $this->belongsTo(ApplicantProfile::class);
    }

    public function histories()
    {
        return $this->hasMany(ApplicationHistory::class)->latest();
    }
}
