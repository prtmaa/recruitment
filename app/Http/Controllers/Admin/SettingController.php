<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nohp' => 'required|string|max:20',
            'teks1' => 'required|string|max:255',
            'teks2' => 'required|string|max:255',
        ]);

        // kalau data belum ada sama sekali, buat baru. Kalau sudah ada, update.
        Setting::updateOrCreate(
            ['id' => $request->id ?? 0],
            $request->only('nama', 'alamat', 'email', 'nohp', 'teks1', 'teks2')
        );

        return redirect()->route('admin.setting.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
