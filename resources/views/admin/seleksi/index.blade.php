@extends('admin.layouts.master')

@section('tittle')
    Manajemen Lamaran
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Dashboard</a>
    <li class="breadcrumb-item active">Lamaran</li>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Lowongan</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Review</th>
                            <th class="text-center">Interview</th>
                            <th class="text-center">Diterima</th>
                            <th class="text-center">Ditolak</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $job->judul }}</span><br>
                                    <small class="text-muted">Tutup: {{ formatTanggalIndo($job->tanggal_tutup) }}</small>
                                </td>
                                <td class="text-center fw-bold">{{ $job->applications_count }}</td>
                                <td class="text-center">{{ $job->pending_count }}
                                </td>
                                <td class="text-center">{{ $job->review_count }}
                                </td>
                                <td class="text-center">{{ $job->interview_count }}
                                </td>
                                <td class="text-center">{{ $job->accepted_count }}
                                </td>
                                <td class="text-center">{{ $job->rejected_count }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.seleksi.show', $job->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat Pelamar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada lowongan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
