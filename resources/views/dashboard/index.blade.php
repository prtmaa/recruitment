<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Recruitment WMUGTT</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('dashboard/img/logo.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('dashboard/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <style>
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px 20px;
            background-color: #fff;
            border: 1px solid #dadce0;
            border-radius: 10px;
            color: #3c4043;
            font-weight: 500;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .google-btn:hover {
            background-color: #e77883ac;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            color: #3c4043;
            transform: translateY(-1px);
        }

        .google-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .job-item {
            transition: box-shadow 0.2s ease;
        }

        .job-item:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        }

        .chevron-icon {
            transition: transform 0.3s ease;
        }

        button[aria-expanded="true"] .chevron-icon {
            transform: rotate(180deg);
        }

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

    <div class="container-fliud bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                {{-- <h1 class="m-0 text-primary">WMUGTT</h1> --}}
                <img class="" src="{{ asset('dashboard/img/logo.png') }}" width="70" alt="">
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#cara" class="nav-item nav-link">Cara Melamar</a>
                    <a href="#loker" class="nav-item nav-link">Loker</a>
                    <a href="{{ url('/auth/google') }}" class="nav-item nav-link">Login</a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->


        <!-- Carousel Start -->
        <div class="container-fluid p-0">
            <div class="owl-carousel header-carousel position-relative">
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="{{ asset('dashboard/img/foto.jpg') }}" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(43, 57, 64, .5);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h2 class=" text-white animated slideInDown mb-4">Bergabunglah Dengan Kami
                                    </h2>
                                    <button type="button"
                                        class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel End -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold" id="exampleModalLabel">Masuk ke Akun</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-2">
                        <p class="text-muted text-center mb-4">Lanjutkan dengan akun Google Anda untuk masuk dengan
                            cepat dan aman.</p>

                        <a href="{{ url('/auth/google') }}" class="google-btn">
                            <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#FFC107"
                                    d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" />
                                <path fill="#FF3D00"
                                    d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" />
                                <path fill="#4CAF50"
                                    d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" />
                                <path fill="#1976D2"
                                    d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" />
                            </svg>
                            <span>Login dengan Google</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
            <div class="container">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">

                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


        <!-- Apply Start -->
        <div class="container-fliud py-5" id="cara">
            <div class="container">
                <h2 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Cara Melamar</h2>
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="cat-item rounded p-4">
                            <i class="fa fa-3x fa-user-check text-primary mb-4"></i>
                            <h6 class="mb-3">1. LOGIN</h6>
                            <span class="mb-0">login menggunakan akun gmail
                                yang biasa digunakan</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="cat-item rounded p-4">
                            <i class="fa fa-3x fa-file-invoice text-primary mb-4"></i>
                            <h6 class="mb-3">2. LENGKAPI DATA</h6>
                            <p class="mb-0">Isi data-data yang dibutuhkan
                                dan siapkan dokumen
                                submit data</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="cat-item rounded p-4">
                            <i class="fa fa-3x fa-paper-plane text-primary mb-4"></i>
                            <h6 class="mb-3">3. KIRIM LAMARAN</h6>
                            <p class="mb-0">Cari lowongan yang sedang dibuka
                                pilih yang sesuai
                                lalu kirim lamaran</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="cat-item rounded p-4">
                            <i class="fa fa-3x fa-bullhorn text-primary mb-4"></i>
                            <h6 class="mb-3">4. TUNGGU PENGUMUMAN</h6>
                            <p class="mb-0">Pantau terus perkembangan
                                dari status lamaran kamu
                                di website ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Apply End -->


        <!-- Jobs Start -->
        <div class="container-fliud py-5" id="loker">
            <div class="container">
                <h2 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Lowongan Yang Tersedia</h2>
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            @forelse ($job as $j)
                                <div class="job-item p-4 mb-4 shadow-sm rounded-3 border-0 bg-white">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-12 col-md-8">
                                            <div class="text-start ps-md-3">
                                                <h5 class="mb-2 fw-semibold">{{ $j->judul }}</h5>

                                                <button
                                                    class="btn btn-sm border d-inline-flex align-items-center gap-2 mb-1"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#jobDetail{{ $j->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="jobDetail{{ $j->id }}">
                                                    <i class="fas fa-info-circle text-primary"></i>
                                                    <span>Lihat Deskripsi & Persyaratan</span>
                                                    <i class="fas fa-chevron-down chevron-icon small"></i>
                                                </button>

                                                <div class="collapse mt-2" id="jobDetail{{ $j->id }}">
                                                    <div class="p-3 rounded-3">
                                                        <p class="mb-2">
                                                            <strong
                                                                class="d-block small text-primary mb-1">Deskripsi</strong>
                                                            {!! $j->deskripsi !!}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong
                                                                class="d-block small text-primary mb-1">Persyaratan</strong>
                                                            {!! $j->persyaratan !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                            <div class="d-flex mb-3">
                                                <a class="btn btn-primary rounded-pill px-4"
                                                    href="{{ url('/auth/google') }}">Apply</a>
                                            </div>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt text-primary me-2"></i>
                                                Berakhir Pada: {{ formatTanggalIndo($j->tanggal_tutup) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada lowongan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jobs End -->


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-3 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 col-md-8">
                        <h5 class="text-white mb-4">KONTAK</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ $setting->nama }}</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ $setting->nohp }}</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ $setting->email }}</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">{{ $setting->nama }}</a>, All Right
                            Reserved.

                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="#">Home</a>
                                <a href="#cara">Cara Melamar</a>
                                <a href="#loker">Loker</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        @include('dashboard.modal')
        <!-- Back to Top -->
        {{-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> --}}
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashboard/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('dashboard/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('dashboard/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('dashboard/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var scamModal = new bootstrap.Modal(document.getElementById('scamWarningModal'));
            scamModal.show();
        });
    </script>

    <!-- Template Javascript -->
    <script src="{{ asset('dashboard/js/main.js') }}"></script>
</body>

</html>
