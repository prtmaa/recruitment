@extends('pelamar.layouts.master')

@section('tittle')
    Lowongan Tersedia
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Lowongan</li>
@endsection

@section('content')
    <div class="container-fluid">

        @if ($lamaranAktif)
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    Masih memiliki lamaran aktif untuk posisi
                    <strong>{{ $lamaranAktif->job->judul ?? '-' }}</strong>.
                    Hanya bisa melamar 1 lowongan dalam satu waktu.
                    Silakan tunggu proses lamaran tersebut selesai sebelum melamar posisi lain.
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">

                @forelse ($job as $j)
                    @php
                        $sudahMelamar = false;
                        if (auth()->check() && auth()->user()->profile) {
                            $sudahMelamar = $j
                                ->applications()
                                ->where('applicant_profile_id', auth()->user()->profile->id)
                                ->exists();
                        }
                        $sudahTutup = $j->tanggal_tutup < now()->toDateString() || !$j->is_active;

                        // user boleh apply kalau: belum apply job ini, job belum tutup,
                        // dan (tidak sedang punya lamaran aktif ATAU lamaran aktifnya ya job ini)
                        $adaLamaranAktifLain = $lamaranAktif && $lamaranAktif->job_id !== $j->id;
                    @endphp

                    <div class="card scam-warning-card shadow-sm border-0">
                        <div class="card-body p-3 p-md-4 job-item">
                            <div class="row g-3 g-md-4 align-items-center">
                                <div class="col-12 col-md-8">
                                    <div class="text-start ps-md-3">
                                        <h5 class="mb-2 fw-semibold job-title">{{ $j->judul }}</h5>

                                        <button
                                            class="btn btn-sm border d-inline-flex align-items-center gap-2 mb-1 flex-wrap"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#jobDetail{{ $j->id }}" aria-expanded="false"
                                            aria-controls="jobDetail{{ $j->id }}">
                                            <i class="fas fa-info-circle text-primary"></i>
                                            <span> Lihat Deskripsi & Persyaratan</span>
                                            <i class="fas fa-chevron-down chevron-icon small"></i>
                                        </button>

                                        <div class="collapse mt-2" id="jobDetail{{ $j->id }}">
                                            <div class="p-3 rounded-3 job-detail-box">
                                                <p class="mb-2">
                                                    <strong class="d-block small text-primary mb-1">Deskripsi</strong>
                                                    {!! $j->deskripsi !!}
                                                </p>
                                                <p class="mb-0">
                                                    <strong class="d-block small text-primary mb-1">Persyaratan</strong>
                                                    {!! $j->persyaratan !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="col-12 col-md-4 d-flex flex-row flex-md-column align-items-center align-items-md-end justify-content-between justify-content-md-center job-action">

                                    @if ($sudahTutup)
                                        <button class="btn btn-secondary rounded-pill px-4 mb-md-3 order-2 order-md-1"
                                            disabled>
                                            Lowongan Ditutup
                                        </button>
                                    @elseif ($sudahMelamar)
                                        <button class="btn btn-outline-success rounded-pill px-4 mb-md-3 order-2 order-md-1"
                                            disabled>
                                            <i class="fas fa-check me-1"></i> Sudah Melamar
                                        </button>
                                    @elseif ($adaLamaranAktifLain)
                                        <button
                                            class="btn btn-outline-secondary rounded-pill px-4 mb-md-3 order-2 order-md-1"
                                            disabled data-bs-toggle="tooltip"
                                            title="Kamu masih punya lamaran aktif di posisi lain">
                                            <i class="fas fa-lock me-1"></i> Tidak Bisa Melamar
                                        </button>
                                    @else
                                        <form id="form-apply-{{ $j->id }}" action="{{ route('jobs.apply', $j->id) }}"
                                            method="POST" class="order-2 order-md-1 mb-md-3">
                                            @csrf
                                            <button type="button" class="btn btn-primary rounded-pill px-4 btn-apply"
                                                data-form="form-apply-{{ $j->id }}" data-judul="{{ $j->judul }}">
                                                Apply
                                            </button>
                                        </form>
                                    @endif

                                    <small class="text-muted order-1 order-md-2 text-end">
                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                        Berakhir Pada:<br class="d-block d-md-none">
                                        {{ formatTanggalIndo($j->tanggal_tutup) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada lowongan lagi.</p>
                    </div>
                @endforelse
            </div>
            <div class="col-md-6">
                <div class="card scam-warning-card shadow-sm border-0">
                    <div class="card-body p-3 p-md-4">

                        {{-- Warning Banner --}}
                        <div class="scam-alert-banner d-flex align-items-center justify-content-center gap-2 mb-3">
                            <i class="fas fa-exclamation-triangle scam-alert-icon"></i>
                            <span class="scam-alert-text">WASPADA PENIPUAN !</span>
                        </div>

                        {{-- Info Box --}}
                        <div class="scam-info-box p-3 p-md-4 mb-3 text-center">
                            <p class="mb-2 fw-bold">
                                {{ $setting->teks1 }}
                            </p>
                        </div>

                        {{-- Body Text --}}
                        <div class="text-center mb-2 scam-body-text">
                            <p class="mb-3 fw-semibold">
                                {{ $setting->teks2 }}
                            </p>
                            <p class="mb-0 fw-bold">
                                {{ $setting->nama }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .job-item {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .job-title {
            font-size: 1.1rem;
            word-break: break-word;
        }

        .job-detail-box {
            background-color: #f8f9fa;
            border: 1px solid #eee;
        }

        .job-detail-box img,
        .job-detail-box table {
            max-width: 100%;
            height: auto;
        }

        .chevron-icon {
            transition: transform 0.3s ease;
        }

        [data-bs-toggle="collapse"][aria-expanded="true"] .chevron-icon {
            transform: rotate(180deg);
        }

        /* ===== Scam Warning Card ===== */
        .tab-class {
            border-radius: 1rem;
            background: #fff;
        }

        .scam-warning-card {
            border-radius: 1rem;
            background: #fff;
        }

        .scam-alert-banner {
            background-color: #8e1a25;
            color: #fff;
            padding: 0.9rem 1rem;
            border-radius: 50px;
        }

        .scam-alert-icon {
            font-size: 1.4rem;
        }

        .scam-alert-text {
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        .scam-info-box {
            background-color: #343a40;
            color: #fff;
            border-radius: 0.75rem;
            font-size: 0.9rem;
        }

        .scam-info-link {
            color: #4dd0ff;
            word-break: break-all;
        }

        .scam-body-text {
            font-size: 0.9rem;
            color: #212529;
        }

        .scale-x-flip {
            transform: scaleX(-1);
        }

        /* ===== Mobile tweaks ===== */
        @media (max-width: 767.98px) {
            .job-item {
                padding: 1rem !important;
            }

            .job-action {
                border-top: 1px solid #eee;
                margin-top: 0.75rem;
                padding-top: 0.75rem;
            }

            .job-action .btn {
                padding-left: 1.25rem;
                padding-right: 1.25rem;
                font-size: 0.9rem;
            }

            .job-action small {
                font-size: 0.75rem;
            }

            .scam-alert-text {
                font-size: 1rem;
            }

            .scam-logo-row {
                justify-content: center !important;
                text-align: center;
            }

            .scam-footer {
                flex-direction: column;
                gap: 0.5rem !important;
            }

            .scam-footer-icon.scale-x-flip {
                display: none;
            }
        }

        @media (max-width: 375px) {
            .job-title {
                font-size: 1rem;
            }

            .scam-info-box,
            .scam-body-text {
                font-size: 0.8rem;
            }
        }
    </style>
@endsection

@push('js')
    <script>
        document.querySelectorAll('.btn-apply').forEach(btn => {
            btn.addEventListener('click', function() {
                const formId = this.dataset.form;
                const judul = this.dataset.judul;

                Swal.fire({
                    title: 'Konfirmasi Lamaran',
                    text: `Yakin ingin melamar posisi ${judul}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lamar',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#8e1a25',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            });
        });
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#8e1a25',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonColor: '#8e1a25'
            });
        </script>
    @endif
@endpush
