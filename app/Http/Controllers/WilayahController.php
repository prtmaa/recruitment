<?php

namespace App\Http\Controllers;

use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class WilayahController extends Controller
{
    public function provinces()
    {
        return Province::orderBy('name')->get(['code', 'name']);
    }

    public function cities($provinceCode)
    {
        return City::where('province_code', $provinceCode)->orderBy('name')->get(['code', 'name']);
    }

    public function districts($cityCode)
    {
        return District::where('city_code', $cityCode)->orderBy('name')->get(['code', 'name']);
    }

    public function villages($districtCode)
    {
        return Village::where('district_code', $districtCode)->orderBy('name')->get(['code', 'name']);
    }
}
