<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_interview' => 'date:Y-m-d',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
