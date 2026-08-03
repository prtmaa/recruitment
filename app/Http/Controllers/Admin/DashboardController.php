<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ringkasan utama
        $totalLowongan = Job::count();
        $lowonganAktif = Job::where('is_active', true)
            ->where('tanggal_tutup', '>=', now()->toDateString())
            ->count();
        $totalPelamar = User::where('role', 'pelamar')->count();
        $totalLamaran = JobApplication::count();

        // ringkasan status lamaran
        $ringkasanStatus = JobApplication::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusDefault = ['pending' => 0, 'review' => 0, 'interview' => 0, 'accepted' => 0, 'rejected' => 0];
        $ringkasanStatus = $ringkasanStatus + $statusDefault;

        // lamaran masuk 7 hari terakhir (untuk grafik)
        $lamaranMingguan = JobApplication::selectRaw('DATE(tanggal_melamar) as tanggal, count(*) as total')
            ->where('tanggal_melamar', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->toDateString();
            $chartLabels[] = now()->subDays($i)->translatedFormat('d M');
            $chartData[] = $lamaranMingguan[$tgl] ?? 0;
        }

        // lowongan segera tutup (dalam 7 hari ke depan)
        $lowonganSegeraTutup = Job::where('is_active', true)
            ->whereBetween('tanggal_tutup', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->withCount('applications')
            ->orderBy('tanggal_tutup')
            ->take(5)
            ->get();

        // lamaran pending yang sudah lama menunggu (>3 hari, belum direview)
        $lamaranPerluPerhatian = JobApplication::with(['job', 'applicantProfile'])
            ->where('status', 'pending')
            ->where('tanggal_melamar', '<=', now()->subDays(3))
            ->orderBy('tanggal_melamar')
            ->take(5)
            ->get();

        // lamaran terbaru masuk
        $lamaranTerbaru = JobApplication::with(['job', 'applicantProfile'])
            ->latest('tanggal_melamar')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLowongan',
            'lowonganAktif',
            'totalPelamar',
            'totalLamaran',
            'ringkasanStatus',
            'chartLabels',
            'chartData',
            'lowonganSegeraTutup',
            'lamaranPerluPerhatian',
            'lamaranTerbaru'
        ));
    }

    public function cariNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|min:3',
        ]);

        $nik = trim($request->input('nik'));

        $profile = ApplicantProfile::with([
            'user',
            'workExperiences',
            'applications' => function ($q) {
                $q->with('job')->orderByDesc('tanggal_melamar');
            },
            'province',
            'city',
            'district',
            'village',
        ])
            ->where('nik', $nik)
            ->first();

        if (!$profile) {
            return response()->json([
                'found' => false,
                'message' => 'NIK tidak ditemukan.',
            ]);
        }

        $statusConfig = [
            'pending'   => ['label' => 'Menunggu', 'class' => 'secondary', 'icon' => 'fa-hourglass-half'],
            'review'    => ['label' => 'Review', 'class' => 'info', 'icon' => 'fa-eye'],
            'interview' => ['label' => 'Interview', 'class' => 'warning', 'icon' => 'fa-comments'],
            'accepted'  => ['label' => 'Diterima', 'class' => 'success', 'icon' => 'fa-check'],
            'rejected'  => ['label' => 'Ditolak', 'class' => 'danger', 'icon' => 'fa-xmark'],
        ];

        return response()->json([
            'found' => true,
            'profile' => [
                'nama'          => $profile->nama,
                'nik'           => $profile->nik,
                'email'         => $profile->user->email ?? '-',
                'no_hp'         => $profile->no_hp ?? '-',
                'foto'          => $profile->foto ? asset('storage/' . $profile->foto) : asset('images/default-avatar.png'),
                'kelamin'       => $profile->kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'tempat_lahir'  => $profile->tempat_lahir ?? '-',
                'tanggal_lahir' => $profile->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->translatedFormat('d F Y') : '-',
                'agama'         => $profile->agama ?? '-',
                'status'        => $profile->status ?? '-',
                'bpjs'          => $profile->bpjs ?? '-',
                'npwp'          => $profile->npwp ?? '-',
                'alamat'        => $profile->alamat ?? '-',
                'domisili'      => $profile->domisili_lengkap ?: '-',
                'pendidikan'    => $profile->pendidikan ?? '-',
                'jurusan'       => $profile->jurusan ?? '-',
                'sekolah'       => $profile->sekolah ?? '-',
                'cv'            => $profile->cv ? asset('storage/' . $profile->cv) : null,
            ],
            'pengalaman' => $profile->workExperiences->map(function ($exp) {
                return [
                    'posisi'         => $exp->posisi,
                    'perusahaan'     => $exp->perusahaan,
                    'kota'           => $exp->kota,
                    'masih_bekerja'  => $exp->masih_bekerja == '1',
                    'mulai_kerja'    => \Carbon\Carbon::parse($exp->mulai_kerja)->translatedFormat('M Y'),
                    'berhenti_kerja' => $exp->berhenti_kerja ? \Carbon\Carbon::parse($exp->berhenti_kerja)->translatedFormat('M Y') : '-',
                    'tanggung_jawab' => $exp->tanggung_jawab,
                ];
            }),
            'riwayat' => $profile->applications->map(function ($app) use ($statusConfig) {
                $cfg = $statusConfig[$app->status] ?? $statusConfig['pending'];
                return [
                    'job_id'          => $app->job_id,
                    'posisi'          => $app->job->judul ?? '-',
                    'tanggal_melamar' => optional($app->tanggal_melamar)->translatedFormat('d M Y'),
                    'status_label'    => $cfg['label'],
                    'status_class'    => $cfg['class'],
                    'status_icon'     => $cfg['icon'],
                    'url'             => route('admin.seleksi.show', $app->job_id),
                ];
            }),
        ]);
    }

    public function chartWilayah()
    {
        $data = ApplicantProfile::selectRaw('domisili_kecamatan, count(*) as total')
            ->whereNotNull('domisili_kecamatan')
            ->groupBy('domisili_kecamatan')
            ->with('district') // relasi belongsTo ke District, key 'code'
            ->orderByDesc('total')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->district->name ?? 'Tidak diketahui',
                    'total' => $item->total,
                ];
            });

        return response()->json($data);
    }
}
