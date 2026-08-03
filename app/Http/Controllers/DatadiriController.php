<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class DatadiriController extends Controller
{
    public function index()
    {
        $profile = ApplicantProfile::with(['workExperiences', 'province', 'city', 'district', 'village'])
            ->where('user_id', Auth::id())
            ->first();

        return view('pelamar.datadiri.index', compact('profile'));
    }

    public function store(Request $request)
    {

        $profile = ApplicantProfile::with('workExperiences')
            ->where('user_id', Auth::id())
            ->first();

        $validated = $request->validate([
            // Upload — wajib hanya kalau belum pernah ada di database
            'foto' => [
                Rule::requiredIf(!$profile || !$profile->foto),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:512',
            ],
            'cv' => [
                Rule::requiredIf(!$profile || !$profile->cv),
                'nullable',
                'mimes:pdf',
                'max:1024',
            ],

            // Data Pribadi — wajib sesuai tanda *
            'nik' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],
            'domisili_provinsi' => ['required', 'string', 'exists:indonesia_provinces,code'],
            'domisili_kabupaten' => ['required', 'string', 'exists:indonesia_cities,code'],
            'domisili_kecamatan' => ['required', 'string', 'exists:indonesia_districts,code'],
            'domisili_desa' => ['required', 'string', 'exists:indonesia_villages,code'],
            'domisili' => ['nullable', 'string', 'max:500'],
            'no_hp' => ['required', 'string', 'max:20'],
            'agama' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],

            // Tidak ada tanda * -> tetap opsional
            'bpjs' => ['nullable', 'string', 'max:50'],
            'npwp' => ['nullable', 'string', 'max:50'],

            // Pendidikan — wajib sesuai tanda *
            'pendidikan' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
            'sekolah' => ['required', 'string', 'max:255'],

            // Pengalaman Kerja
            'pengalaman' => ['nullable', 'array'],
            'pengalaman.*.perusahaan' => ['nullable', 'string', 'max:255'],
            'pengalaman.*.posisi' => ['nullable', 'string', 'max:255'],
            'pengalaman.*.kota' => ['nullable', 'string', 'max:255'],
            'pengalaman.*.mulai_kerja' => ['nullable', 'date'],
            'pengalaman.*.berhenti_kerja' => ['nullable', 'date'],
            'pengalaman.*.masih_bekerja' => ['nullable'],
            'pengalaman.*.tanggung_jawab' => ['nullable', 'string'],
        ], [
            // pesan custom biar lebih ramah, opsional
            'foto.required' => 'Foto wajib diupload.',
            'cv.required' => 'CV wajib diupload.',
        ]);

        $profile = $profile ?? new ApplicantProfile(['user_id' => Auth::id()]);

        $profile->fill([
            'user_id' => Auth::id(),
            'nik' => $validated['nik'],
            'nama' => strtoupper($validated['nama']),
            'kelamin' => $validated['kelamin'],
            'tempat_lahir' => strtoupper($validated['tempat_lahir']) ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'domisili_provinsi' => $validated['domisili_provinsi'] ?? null,
            'domisili_kabupaten' => $validated['domisili_kabupaten'] ?? null,
            'domisili_kecamatan' => $validated['domisili_kecamatan'] ?? null,
            'domisili_desa' => $validated['domisili_desa'] ?? null,
            'domisili' => $validated['domisili'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'status' => $validated['status'] ?? null,
            'bpjs' => $validated['bpjs'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'pendidikan' => $validated['pendidikan'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
            'sekolah' => $validated['sekolah'] ?? null,
        ]);

        // Upload foto (hapus file lama kalau ada)
        if ($request->hasFile('foto')) {
            if ($profile->foto) {
                Storage::disk('public')->delete($profile->foto);
            }
            $profile->foto = $request->file('foto')->store('foto', 'public');
        }

        // Upload CV (hapus file lama kalau ada)
        if ($request->hasFile('cv')) {
            if ($profile->cv) {
                Storage::disk('public')->delete($profile->cv);
            }
            $profile->cv = $request->file('cv')->store('cv', 'public');
        }

        // Hitung kelengkapan profil (is_complete)
        $wajibTerisi = [
            $profile->nik,
            $profile->nama,
            $profile->kelamin,
            $profile->tempat_lahir,
            $profile->tanggal_lahir,
            $profile->alamat,
            $profile->domisili_provinsi,
            $profile->domisili_kabupaten,
            $profile->domisili_kecamatan,
            $profile->domisili_desa,
            $profile->no_hp,
            $profile->agama,
            $profile->status,
            $profile->pendidikan,
            $profile->jurusan,
            $profile->sekolah,
            $profile->foto,
            $profile->cv,
        ];
        $adaPengalaman = !empty($validated['pengalaman']);

        $profile->is_complete = collect($wajibTerisi)->every(fn($v) => !empty($v)) && $adaPengalaman;

        $profile->save();

        // Simpan ulang pengalaman kerja: hapus yang lama, insert yang baru
        $profile->workExperiences()->delete();

        if (!empty($validated['pengalaman'])) {
            foreach ($validated['pengalaman'] as $item) {
                // Lewati baris yang kosong sepenuhnya (misal blok ditambah tapi tidak diisi)
                if (empty($item['perusahaan']) && empty($item['posisi']) && empty($item['kota'])) {
                    continue;
                }

                $profile->workExperiences()->create([
                    'perusahaan' => $item['perusahaan'] ?? '',
                    'posisi' => $item['posisi'] ?? '',
                    'kota' => $item['kota'] ?? '',
                    'mulai_kerja' => $item['mulai_kerja'] ?? now(),
                    'berhenti_kerja' => empty($item['masih_bekerja']) ? ($item['berhenti_kerja'] ?? null) : null,
                    'masih_bekerja' => !empty($item['masih_bekerja']) ? '1' : '0',
                    'tanggung_jawab' => $item['tanggung_jawab'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('datadiri.index')
            ->with('success', 'Data diri berhasil disimpan.');
    }
}
