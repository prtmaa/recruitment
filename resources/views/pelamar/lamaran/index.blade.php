@extends('pelamar.layouts.master')

@section('tittle')
    Lamaran Saya
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Lamaran</li>
@endsection

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">

                @forelse ($lamaran as $item)
                    @php
                        $statusConfig = [
                            'pending' => ['label' => 'Menunggu', 'class' => 'secondary', 'icon' => 'fa-hourglass-half'],
                            'review' => [
                                'label' => 'Sedang Ditinjau',
                                'class' => 'info',
                                'icon' => 'fa-eye',
                            ],
                            'interview' => ['label' => 'Interview', 'class' => 'warning', 'icon' => 'fa-comments'],
                            'accepted' => ['label' => 'Diterima', 'class' => 'success', 'icon' => 'fa-check'],
                            'rejected' => ['label' => 'Ditolak', 'class' => 'danger', 'icon' => 'fa-xmark'],
                        ];
                        $config = $statusConfig[$item->status] ?? $statusConfig['pending'];

                        // urutan tahapan untuk progress bar
                        $tahapan = ['pending', 'review', 'interview', 'accepted'];
                        $currentIndex = array_search($item->status, $tahapan);
                        $isRejected = $item->status === 'rejected';
                    @endphp

                    <div class="card shadow-sm border-0 rounded-3 mb-4 lamaran-card">
                        <div class="card-body p-3 p-md-4">

                            {{-- Header --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1">{{ $item->job->judul ?? '-' }}</h5>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        Dilamar pada {{ $item->tanggal_melamar?->translatedFormat('d F Y') }}
                                    </small>
                                </div>
                                <span class="badge rounded-pill text-bg-{{ $config['class'] }} px-3 py-2">
                                    <i class="fas {{ $config['icon'] }} me-1"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>

                            {{-- Progress Tahapan --}}
                            @if (!$isRejected)
                                <div class="lamaran-progress mb-3">
                                    <div class="d-flex justify-content-between position-relative">
                                        @foreach ($tahapan as $index => $step)
                                            <div
                                                class="progress-step text-center {{ $index <= $currentIndex ? 'active' : '' }}">
                                                <div class="progress-dot"></div>
                                                <small class="d-block mt-1 text-capitalize">
                                                    {{ $statusConfig[$step]['label'] }}
                                                </small>
                                            </div>
                                        @endforeach
                                        <div class="progress-line">
                                            <div class="progress-line-fill"
                                                style="width: {{ $currentIndex >= 0 ? ($currentIndex / (count($tahapan) - 1)) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger py-2 px-3 mb-3 small">
                                    <i class="fas fa-xmark me-1"></i>
                                    Lamaran ini tidak dilanjutkan ke tahap berikutnya.
                                </div>
                            @endif

                            @if ($item->catatan_hrd)
                                <div class="alert alert-light border small mb-3">
                                    <strong class="d-block mb-1"><i class="fas fa-note-sticky me-1"></i> Catatan:</strong>
                                    {{ $item->catatan_hrd }}
                                </div>
                            @endif

                            {{-- Toggle Riwayat --}}
                            <button class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#riwayat{{ $item->id }}">
                                <i class="fas fa-clock-rotate-left"></i>
                                Lihat Riwayat Status
                                <i class="fas fa-chevron-down chevron-icon small"></i>
                            </button>

                            <div class="collapse mt-3" id="riwayat{{ $item->id }}">
                                <div class="riwayat-timeline">
                                    @forelse ($item->histories as $history)
                                        <div class="timeline-item">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between flex-wrap">
                                                    <span class="fw-semibold text-capitalize">
                                                        {{ $statusConfig[$history->status_baru]['label'] ?? $history->status_baru }}
                                                    </span>
                                                    <small class="text-muted">
                                                        {{ $history->created_at->translatedFormat('d M Y, H:i') }}
                                                    </small>
                                                </div>
                                                @if ($history->keterangan)
                                                    <p class="mb-0 small text-muted mt-1">{{ $history->keterangan }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">Belum ada riwayat.</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Anda belum melamar pekerjaan apapun.</p>
                        <a href="{{ url('loker') }}" class="btn btn-primary rounded-pill mt-3 px-4">
                            Cari Lowongan
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    <style>
        .lamaran-card {
            transition: box-shadow 0.2s ease;
        }

        .lamaran-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }

        .chevron-icon {
            transition: transform 0.3s ease;
        }

        [data-bs-toggle="collapse"][aria-expanded="true"] .chevron-icon {
            transform: rotate(180deg);
        }

        /* Progress steps */
        .lamaran-progress {
            padding: 0 0.5rem;
        }

        .progress-step {
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .progress-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #dee2e6;
            margin: 0 auto;
            border: 3px solid #fff;
            box-shadow: 0 0 0 1px #dee2e6;
        }

        .progress-step.active .progress-dot {
            background-color: #8e1a25;
            box-shadow: 0 0 0 1px #8e1a25;
        }

        .progress-step small {
            font-size: 0.7rem;
            color: #adb5bd;
        }

        .progress-step.active small {
            color: #8e1a25;
            font-weight: 600;
        }

        .progress-line {
            position: absolute;
            top: 8px;
            left: 8%;
            right: 8%;
            height: 3px;
            background-color: #dee2e6;
            z-index: 1;
        }

        .progress-line-fill {
            height: 100%;
            background-color: #8e1a25;
            transition: width 0.3s ease;
        }

        /* Timeline riwayat */
        .riwayat-timeline {
            border-left: 2px solid #dee2e6;
            padding-left: 1rem;
            margin-left: 0.4rem;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -1.28rem;
            top: 0.2rem;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #8e1a25;
        }

        .timeline-content {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.6rem 0.9rem;
        }

        @media (max-width: 575.98px) {
            .progress-step small {
                font-size: 0.6rem;
            }

            .progress-dot {
                width: 12px;
                height: 12px;
            }
        }
    </style>
@endsection
