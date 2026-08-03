<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class ApplicantProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function noHpWa(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->no_hp) {
                    return null;
                }

                $number = preg_replace('/\D/', '', $this->no_hp);

                if (str_starts_with($number, '0')) {
                    $number = '62' . substr($number, 1);
                } elseif (str_starts_with($number, '8')) {
                    $number = '62' . $number;
                }

                return $number;
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperiences::class);
    }


    public function applications()
    {
        return $this->hasMany(JobApplication::class)->with('job')->latest('tanggal_melamar');
    }

    public function province()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'domisili_provinsi', 'code');
    }

    public function city()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'domisili_kabupaten', 'code');
    }

    public function district()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'domisili_kecamatan', 'code');
    }

    public function village()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Village::class, 'domisili_desa', 'code');
    }

    protected $appends = ['domisili_lengkap'];

    public function getDomisiliLengkapAttribute()
    {
        return collect([
            $this->domisili,
            $this->village->name ?? null,
            $this->district->name ?? null,
            $this->city->name ?? null,
            $this->province->name ?? null,
        ])->filter()->implode(', ');
    }

    // public function jobs()
    // {
    //     return $this->belongsToMany(
    //         Job::class,
    //         'job_applications'
    //     )->withPivot('status', 'catatan_hrd', 'tanggal_melamar')->withTimestamps();
    // }
}
