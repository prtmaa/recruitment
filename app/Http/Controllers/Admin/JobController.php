<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\InterviewSchedule;

class JobController extends Controller
{
    public function index()
    {
        return view('admin.job.index');
    }

    public function data()
    {
        $job = Job::orderBy('created_at', 'desc')->get();

        return datatables()
            ->of($job)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($job) {
                return formatTanggalIndo($job->tanggal_tutup);
            })
            ->addColumn('deskripsi', function ($job) {
                return $job->deskripsi;
            })
            ->addColumn('persyaratan', function ($job) {
                return $job->persyaratan;
            })
            ->addColumn('status', function ($job) {

                if (Carbon::parse($job->tanggal_tutup)->isPast()) {
                    return '<span class="badge badge-danger">Deadline</span>';
                }

                if ($job->is_active) {
                    return '<span class="badge badge-success">Publish</span>';
                }

                return '<span class="badge badge-warning">Unpublish</span>';
            })
            ->addColumn('aksi', function ($job) {
                return '
                    <div class="btn-group">
                        <button type="button" onclick="inputInterview(`' . route('admin.job.interview.get', $job->id) . '`, `' . route('admin.job.interview.save', $job->id) . '`, `' . $job->judul . '`)" class="btn btn-sm btn-warning" title="Jadwal Interview">
                            <i class="fa fa-calendar-check"></i>
                        </button>
                        <button type="button" onclick="editForm(`' . route('admin.job.update', $job->id) . '`)" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pen"></i></button>
                        <button type="button" onclick="deleteData(`' . route('admin.job.destroy', $job->id) . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
            })
            ->rawColumns(['aksi', 'tanggal', 'deskripsi', 'persyaratan', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $job = new Job();

        $lastJob = Job::latest('id')->first();

        if ($lastJob) {
            $lastNumber = (int) substr($lastJob->kode, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kode = 'JOB' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $job->kode = $kode;
        $job->judul = $request->judul;
        $job->deskripsi = $request->deskripsi;
        $job->persyaratan = $request->persyaratan;
        $job->tanggal_tutup = $request->tanggal_tutup;
        $job->created_by = auth()->id();
        $job->is_active = $request->boolean('is_active');
        $job->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $job = Job::find($id);

        return response()->json($job);
    }

    public function update(Request $request, $id)
    {
        $job = Job::find($id);
        $job->judul = $request->judul;
        $job->deskripsi = $request->deskripsi;
        $job->persyaratan = $request->persyaratan;
        $job->tanggal_tutup = $request->tanggal_tutup;
        $job->is_active = $request->boolean('is_active');
        $job->update();

        return response()->json('Data berhasil diubah', 200);
    }

    public function destroy($id)
    {
        $job = Job::find($id);
        $job->delete();

        return response()->json('Data berhasil dihapus', 200);
    }

    public function getInterview(Job $job)
    {
        $jadwal = $job->interviewSchedule;

        return response()->json($jadwal);
    }

    // Simpan / update jadwal interview
    public function saveInterview(Request $request, Job $job)
    {
        $request->validate([
            'tanggal_interview' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable|after:jam_mulai',
            'tempat' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:1000',
        ]);

        InterviewSchedule::updateOrCreate(
            ['job_id' => $job->id],
            $request->only('tanggal_interview', 'jam_mulai', 'jam_selesai', 'tempat', 'catatan')
        );

        return response()->json(['message' => 'Jadwal interview berhasil disimpan.']);
    }
}
