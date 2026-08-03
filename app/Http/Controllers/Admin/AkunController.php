<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index()
    {
        return view('admin.akun.index');
    }
    public function data()
    {
        $query = User::where('role', 'pelamar')->with('profile');

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', fn($akun) => $akun->profile->nama ?? $akun->name)
            ->addColumn('no_hp', fn($akun) => $akun->profile->no_hp ?? '-')
            ->addColumn('profil', function ($akun) {
                return $akun->profile && $akun->profile->is_complete
                    ? '<span class="badge bg-success">Lengkap</span>'
                    : '<span class="badge bg-secondary">Belum Lengkap</span>';
            })
            ->addColumn('bergabung', fn($akun) => $akun->created_at->translatedFormat('d M Y'))
            ->addColumn('aksi', function ($akun) {
                return '
            <div class="btn-group">
                <button type="button" onclick="lihatDetail(`' . route('admin.akun.detail', $akun->id) . '`)" class="btn btn-sm btn-info" title="Lihat Detail">
                    <i class="fa fa-eye"></i>
                </button>
                <button type="button" onclick="deleteData(`' . route('admin.akun.destroy', $akun->id) . '`)" class="btn btn-sm btn-danger" title="Hapus Akun">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            ';
            })
            ->filterColumn('nama_lengkap', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhereHas('profile', function ($sub) use ($keyword) {
                            $sub->where('nama', 'like', "%{$keyword}%")
                                ->orWhere('nik', 'like', "%{$keyword}%");
                        });
                });
            })
            ->filterColumn('no_hp', function ($query, $keyword) {
                $query->whereHas('profile', function ($q) use ($keyword) {
                    $q->where('no_hp', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['profil', 'aksi'])
            ->make(true);
    }

    public function detail(User $akun)
    {
        $akun->load([
            'profile.workExperiences',
            'profile.applications.job',
            'profile.province',
            'profile.city',
            'profile.district',
            'profile.village',
        ]);

        return response()->json($akun);
    }

    public function destroy(User $akun)
    {
        $akun->delete();

        return response()->json(['message' => 'Akun pelamar berhasil dihapus.']);
    }
}
