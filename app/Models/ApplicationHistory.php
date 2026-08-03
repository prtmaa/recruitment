<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    protected $guarded = [];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
