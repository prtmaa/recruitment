@extends('pelamar.layouts.master')

@section('tittle')
    Home
@endsection

@section('content')
    <div class="container-fluid">

        {{-- Alert profil --}}
        @if (!$profile || !$profile->is_complete)
            <div
                class="alert alert-primary d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 rounded-3 border-0 shadow-sm">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-triangle-exclamation fa-lg"></i>
                    <div>
                        <strong class="d-block">Profil Anda belum lengkap</strong>
                        <small>Lengkapi profil terlebih dahulu agar dapat melamar pekerjaan.</small>
                    </div>
                </div>
                <a href="{{ url('datadiri') }}" class="text-white fw-semibold">
                    Lengkapi Sekarang >>
                </a>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-4">
            <h4 class="fw-semibold mb-1">Halo, {{ Auth::user()->name }} 👋</h4>
            <p class="text-muted mb-0">Berikut ringkasan aktivitas lamaran Anda.</p>
        </div>

        {{-- Stat cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file fa-lg text-primary mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $totalLamaran }}</h4>
                        <small class="text-muted">Total Lamaran</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-hourglass-half fa-lg text-primary mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $ringkasanStatus['pending'] + $ringkasanStatus['review'] }}</h4>
                        <small class="text-muted">Diproses</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-comments fa-lg text-primary mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $ringkasanStatus['interview'] }}</h4>
                        <small class="text-muted">Interview</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-check fa-lg text-primary mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $ringkasanStatus['accepted'] }}</h4>
                        <small class="text-muted">Diterima</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">

            {{-- Interview mendatang --}}
            @if ($interviewMendatang->isNotEmpty())
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 interview-alert-card">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-calendar-check text-primary me-1"></i>
                                Jadwal Interview Mendatang
                            </h6>
                            @foreach ($interviewMendatang as $item)
                                @php $jadwal = $item->job->interviewSchedule; @endphp
                                <div
                                    class="d-flex justify-content-between align-items-center flex-wrap gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div>
                                        <span class="fw-semibold d-block">{{ $item->job->judul }}</span>
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $jadwal->tanggal_interview->translatedFormat('l, d F Y') }},
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} WIB
                                            &middot;
                                            <i class="fas fa-location-dot me-1"></i>{{ $jadwal->tempat }}
                                        </small>
                                    </div>
                                    <a href="{{ url('lamaran') }}" class="btn btn-sm btn-primary">
                                        Detail
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Lamaran terbaru --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0">Lamaran Terbaru</h6>
                        <a href="{{ url('lamaran') }}" class="small">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @if ($lamaranTerbaru->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Posisi</th>
                                            <th>Tgl Lamar</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $statusConfig = [
                                                'pending' => [
                                                    'label' => 'Menunggu',
                                                    'class' => 'secondary',
                                                    'icon' => 'fa-hourglass-half',
                                                ],
                                                'review' => [
                                                    'label' => 'Review',
                                                    'class' => 'info',
                                                    'icon' => 'fa-eye',
                                                ],
                                                'interview' => [
                                                    'label' => 'Interview',
                                                    'class' => 'warning',
                                                    'icon' => 'fa-comments',
                                                ],
                                                'accepted' => [
                                                    'label' => 'Diterima',
                                                    'class' => 'success',
                                                    'icon' => 'fa-check',
                                                ],
                                                'rejected' => [
                                                    'label' => 'Ditolak',
                                                    'class' => 'danger',
                                                    'icon' => 'fa-xmark',
                                                ],
                                            ];
                                        @endphp
                                        @foreach ($lamaranTerbaru as $item)
                                            @php $cfg = $statusConfig[$item->status] ?? $statusConfig['pending']; @endphp
                                            <tr>
                                                <td>{{ $item->job->judul ?? '-' }}</td>
                                                <td>
                                                    <small class="">
                                                        {{ formatTanggalIndo($item->tanggal_melamar) }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $cfg['class'] }}">
                                                        <i
                                                            class="fas {{ $cfg['icon'] }} me-1  mr-1"></i>{{ $cfg['label'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-3">Anda belum pernah melamar pekerjaan.</p>
                                <a href="{{ url('loker') }}" class="btn btn-primary btn-sm rounded-pill px-4">Cari
                                    Lowongan</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .stat-card {
            transition: transform 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .interview-alert-card {
            border-left: 4px solid #8e1a25 !important;
        }
    </style>
@endsection
