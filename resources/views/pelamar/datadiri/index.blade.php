@extends('pelamar.layouts.master')

@section('tittle')
    Data Diri
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/home') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Data Diri</li>
@endsection

@section('content')
    <style>
        :root {
            --pf-primary: #8e1a25;
            --pf-primary-soft: #db4d5b;
            --pf-text: #1e293b;
            --pf-muted: #64748b;
            --pf-border: #e2e8f0;
            --pf-bg: #f8fafc;
        }

        .pf-card {
            border: none;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px -12px rgba(15, 23, 42, 0.10);
            overflow: hidden;
        }

        /* Tabs — segmented pill control */
        .pf-tabs {
            display: flex;
            gap: .25rem;
            padding: .5rem;
            margin: 0;
            background: var(--pf-bg);
            border-bottom: 1px solid var(--pf-border);
            list-style: none;
        }

        .pf-tabs .nav-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            border: none;
            border-radius: .75rem;
            padding: .6rem 1.1rem;
            font-size: .9rem;
            font-weight: 500;
            color: var(--pf-muted);
            background: transparent;
            transition: background .15s ease, color .15s ease;
        }

        .pf-tabs .nav-link i {
            font-size: 1rem;
        }

        .pf-tabs .nav-link:hover {
            color: var(--pf-text);
            background: #eef2f7;
        }

        .pf-tabs .nav-link.active {
            color: var(--pf-primary);
            background: #fff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }

        /* Section heading inside each tab */
        .pf-section-title {
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--pf-muted);
            margin-bottom: 1.25rem;
        }

        /* Labels + inputs */
        .pf-card .form-label {
            display: block;
            font-size: .85rem;
            font-weight: 500;
            color: var(--pf-text);
            margin-bottom: .4rem;
        }

        .pf-card .form-control,
        .pf-card .form-select {
            display: block;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid var(--pf-border);
            border-radius: .75rem;
            background: #fbfcfe;
            font-size: .92rem;
            padding: .6rem .9rem;
        }

        /* Custom select arrow so it renders consistently even without full Bootstrap CSS loaded */
        .pf-card select.form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .9rem center;
            background-size: 14px 14px;
            padding-right: 2.25rem;
            cursor: pointer;
        }

        .pf-card .form-control:focus,
        .pf-card .form-select:focus {
            border-color: var(--pf-primary);
            box-shadow: 0 0 0 .2rem var(--pf-primary-soft);
            background-color: #fff;
            outline: none;
        }

        .pf-card textarea.form-control {
            border-radius: .75rem;
            resize: vertical;
        }

        .pf-card .form-control[readonly] {
            background: #f1f5f9;
            color: var(--pf-muted);
        }

        .pf-hint {
            font-size: .78rem;
            color: var(--pf-muted);
            margin-top: .3rem;
        }

        .pf-footer {
            background: var(--pf-bg);
            border-top: 1px solid var(--pf-border);
            padding: 1rem 1.5rem;
        }

        .pf-btn-primary {
            background: var(--pf-primary);
            border: none;
            border-radius: .75rem;
            box-shadow: 0 1px 2px rgba(79, 70, 229, .25);
            transition: background .15s ease, transform .05s ease;
        }

        .pf-btn-primary:hover {
            background: #8e1a25;
        }

        .pf-btn-primary:active {
            transform: translateY(1px);
        }

        .pf-btn-ghost {
            border-radius: .75rem;
            font-weight: 500;
            color: var(--pf-muted);
            border: 1px solid var(--pf-border);
            background: #fff;
        }

        .pf-btn-ghost:hover {
            background: var(--pf-bg);
            color: var(--pf-text);
        }

        .pf-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--pf-muted);
        }

        .pf-empty i {
            font-size: 2rem;
            color: #cbd5e1;
            display: block;
            margin-bottom: .75rem;
        }

        .form-control,
        .form-select {
            margin-bottom: 0.75rem;
        }

        /* ===== Header Profil ===== */
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding: 1.5rem 0;
        }

        .profile-header-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: .9rem;
            object-fit: cover;
            background: #e2e8f0;
        }

        .profile-email {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: var(--pf-muted);
            font-size: .95rem;
        }

        .btn-download-cv {
            background: var(--pf-green);
            color: #fff;
            border: none;
            border-radius: .6rem;
            padding: .55rem 1.25rem;
            font-weight: 600;
            font-size: .9rem;
            white-space: nowrap;
        }

        .btn-download-cv:hover {
            background: #16a34a;
            color: #fff;
        }

        .badge-upload-status {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            background: #fef9c3;
            color: #b45309;
            border-radius: .6rem;
            padding: .55rem 1.1rem;
            font-weight: 600;
            font-size: .88rem;
            white-space: nowrap;
        }

        .badge-upload-status .close-x {
            cursor: pointer;
            font-weight: 700;
            color: #b45309;
            opacity: .7;
        }

        .badge-upload-status .close-x:hover {
            opacity: 1;
        }

        .profile-completion {
            min-width: 260px;
        }

        .profile-completion-label {
            display: flex;
            justify-content: space-between;
            font-size: .85rem;
            color: var(--pf-muted);
            margin-bottom: .4rem;
        }

        .profile-completion-label strong {
            color: var(--pf-text);
            font-weight: 700;
        }

        .progress-thin {
            height: 6px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .progress-thin-bar {
            height: 100%;
            background: var(--pf-primary);
            border-radius: 999px;
            transition: width .3s ease;
        }

        /* ===== Card Upload Foto & CV ===== */
        .upload-card {
            background: #fff;
            border: 1px solid var(--pf-border);
            border-radius: 1rem;
            padding: 1.75rem;
        }

        .upload-group {
            margin-bottom: 1.5rem;
        }

        .upload-group:last-child {
            margin-bottom: 0;
        }

        .upload-label {
            font-size: .95rem;
            font-weight: 600;
            color: var(--pf-text);
            margin-bottom: .6rem;
            display: block;
        }

        .upload-group input[type="file"] {
            display: block;
            width: 100%;
            max-width: 480px;
            font-size: .9rem;
            color: var(--pf-text);
            border: 1px solid var(--pf-border);
            border-radius: .6rem;
            padding: .1rem;
            background: #fff;
        }

        .upload-group input[type="file"]::file-selector-button {
            background: #f1f5f9;
            border: none;
            border-right: 1px solid var(--pf-border);
            padding: .6rem 1rem;
            margin-right: 1rem;
            font-weight: 600;
            font-size: .88rem;
            color: var(--pf-text);
            cursor: pointer;
        }

        .upload-group input[type="file"]::file-selector-button:hover {
            background: #e2e8f0;
        }

        .upload-hint {
            font-size: .8rem;
            color: var(--pf-muted);
            margin-top: .45rem;
        }
    </style>

    <form action="{{ route('datadiri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    Isi Ulang Tab Pengalaman
                </div>
            @endif

            <!-- ===== Card Upload Foto & CV ===== -->
            <div class="row upload-card">
                <div class="col-md-4">
                    <!-- ===== Header Profil ===== -->
                    <div class="profile-header">
                        <div class="profile-header-left">
                            <img id="header-avatar-preview"
                                src="{{ $profile && $profile->foto ? asset('storage/' . $profile->foto) : asset('dashboard/img/user.jpg') }}"
                                alt="Foto Profil" class="profile-avatar">

                            <div>
                                <div class="profile-email mb-2">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>

                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge-upload-status" id="upload-status-badge">
                                        Upload Foto &amp; Dokumen ( <span id="upload-count">0</span> / 2 )
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-completion col-md-6 mb-5">
                        <div class="profile-completion-label">
                            <span>Kelengkapan Profil</span>
                            <strong id="completion-percent">0%</strong>
                        </div>
                        <div class="progress-thin">
                            <div class="progress-thin-bar" id="completion-bar" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="upload-group">
                        <label class="upload-label" for="foto">Upload Foto <span class="text-danger">*</span></label>
                        <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png"
                            onchange="handleFotoChange(event)">
                        <p class="upload-hint">.jpg, .png, .jpeg - max: 512kb @if ($profile && $profile->foto)
                                <span class="upload-hint text-success"> - Sudah Terupload</span>
                            @endif
                        </p>

                    </div>

                    <div class="upload-group">
                        <label class="upload-label" for="cv">Upload Dokumen <span class="text-danger">*</span></label>
                        <input type="file" id="cv" name="cv" accept=".pdf" onchange="handleCvChange(event)">
                        <p class="upload-hint">Silahkan scan KTP, KK, Ijazah, Surat sehat terbaru, CV dalam 1 file - max:
                            2048kb @if ($profile && $profile->cv)
                                <span class="upload-hint text-success"> - Sudah Terupload</span>
                            @endif
                        </p>

                    </div>
                </div>
            </div>

            <!-- Card tabs Data Pribadi / Pendidikan / Pengalaman tetap di bawah sini seperti sebelumnya -->

        </div>

        <div class="row">
            <div class="col-12">
                <div class="card pf-card">
                    <div class="card-header p-0 border-bottom-0 bg-transparent">
                        <ul class="nav pf-tabs" id="profile-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="datapribadi-tab" data-bs-toggle="tab"
                                    data-bs-target="#datapribadi" type="button" role="tab" aria-controls="datapribadi"
                                    aria-selected="true">
                                    Data Pribadi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tabpendidikan-tab" data-bs-toggle="tab"
                                    data-bs-target="#tabpendidikan" type="button" role="tab"
                                    aria-controls="tabpendidikan" aria-selected="false">
                                    Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pengalaman-tab" data-bs-toggle="tab"
                                    data-bs-target="#pengalaman" type="button" role="tab" aria-controls="pengalaman"
                                    aria-selected="false">
                                    Pengalaman
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="profile-tabs-content">

                            <div class="tab-pane fade show active" id="datapribadi" role="tabpanel"
                                aria-labelledby="datapribadi-tab">

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="nik">NIK (No KTP) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nik" name="nik"
                                            value="{{ old('nik', $profile->nik ?? '') }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="nama">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ old('nama', $profile->nama ?? '') }}" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="kelamin">Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select name="kelamin" id="kelamin" class="form-select" required>
                                            <option value="" disabled selected></option>
                                            <option value="L"
                                                {{ old('kelamin', $profile->kelamin ?? '') == 'L' ? 'selected' : '' }}>
                                                Laki-laki
                                            </option>
                                            <option value="P"
                                                {{ old('kelamin', $profile->kelamin ?? '') == 'P' ? 'selected' : '' }}>
                                                Perempuan
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="tempat_lahir">Tempat Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                            value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tanggal_lahir"
                                            name="tanggal_lahir"
                                            value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="alamat">Alamat (Sesuai KTP) <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Domisili <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select name="domisili_provinsi" id="domisili_provinsi" class="form-select"
                                            data-selected="{{ old('domisili_provinsi', $profile->domisili_provinsi ?? '') }}"
                                            required>
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select name="domisili_kabupaten" id="domisili_kabupaten" class="form-select"
                                            data-selected="{{ old('domisili_kabupaten', $profile->domisili_kabupaten ?? '') }}"
                                            required disabled>
                                            <option value="">Pilih Kabupaten</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select name="domisili_kecamatan" id="domisili_kecamatan" class="form-select"
                                            data-selected="{{ old('domisili_kecamatan', $profile->domisili_kecamatan ?? '') }}"
                                            required disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select name="domisili_desa" id="domisili_desa" class="form-select"
                                            data-selected="{{ old('domisili_desa', $profile->domisili_desa ?? '') }}"
                                            required disabled>
                                            <option value="">Pilih Desa</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" id="domisili" name="domisili" rows="2"
                                            placeholder="Detail Alamat (Jalan, RT/RW, dll)">{{ old('domisili', $profile->domisili ?? '') }}</textarea>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="no_hp">No HP <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="no_hp" name="no_hp"
                                            value="{{ old('no_hp', $profile->no_hp ?? '') }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="agama">Agama <span
                                                class="text-danger">*</span></label>
                                        <select name="agama" id="agama" class="form-select" required>
                                            <option value="" disabled selected></option>
                                            <option value="Islam"
                                                {{ old('agama', $profile->agama ?? '') == 'Islam' ? 'selected' : '' }}>
                                                Islam</option>
                                            <option value="Kristen"
                                                {{ old('agama', $profile->agama ?? '') == 'Kristen' ? 'selected' : '' }}>
                                                Kristen</option>
                                            <option value="Katholik"
                                                {{ old('agama', $profile->agama ?? '') == 'Katholik' ? 'selected' : '' }}>
                                                Katholik</option>
                                            <option value="Hindu"
                                                {{ old('agama', $profile->agama ?? '') == 'Hindu' ? 'selected' : '' }}>
                                                Hindu</option>
                                            <option value="Budha"
                                                {{ old('agama', $profile->agama ?? '') == 'Budha' ? 'selected' : '' }}>
                                                Budha</option>
                                            <option value="Konghucu"
                                                {{ old('agama', $profile->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>
                                                Konghucu</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="status">Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="" disabled selected></option>
                                            <option value="Kawin"
                                                {{ old('status', $profile->status ?? '') == 'Kawin' ? 'selected' : '' }}>
                                                Kawin
                                            </option>
                                            <option value="Belum Kawin"
                                                {{ old('status', $profile->status ?? '') == 'Belum Kawin' ? 'selected' : '' }}>
                                                Belum Kawin
                                            </option>
                                            <option value="Cerai Hidup"
                                                {{ old('status', $profile->status ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>
                                                Cerai Hidup
                                            </option>
                                            <option value="Cerai Mati"
                                                {{ old('status', $profile->status ?? '') == 'Cerai Mati' ? 'selected' : '' }}>
                                                Cerai Mati
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="bpjs">No BPJS</label>
                                        <input type="number" class="form-control" id="bpjs" name="bpjs"
                                            value="{{ old('bpjs', $profile->bpjs ?? '') }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="npwp">No NPWP</label>
                                        <input type="number" class="form-control" id="npwp" name="npwp"
                                            value="{{ old('npwp', $profile->npwp ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabpendidikan" role="tabpanel"
                                aria-labelledby="tabpendidikan-tab">

                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="pendidikan">Pendidikan
                                            Terakhir <span class="text-danger">*</span></label>
                                        <select name="pendidikan" id="pendidikan" class="form-select" required>
                                            <option value="" disabled selected></option>
                                            <option value="SMP"
                                                {{ old('pendidikan', $profile->pendidikan ?? '') == 'SMP' ? 'selected' : '' }}>
                                                SMP</option>
                                            <option value="SMA/SMK"
                                                {{ old('pendidikan', $profile->pendidikan ?? '') == 'SMA/SMK' ? 'selected' : '' }}>
                                                SMA/SMK</option>
                                            <option value="S1"
                                                {{ old('pendidikan', $profile->pendidikan ?? '') == 'S1' ? 'selected' : '' }}>
                                                S1</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="jurusan">Jurusan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="jurusan" name="jurusan"
                                            required value="{{ old('jurusan', $profile->jurusan ?? '') }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="sekolah">Sekolah <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="sekolah" name="sekolah"
                                            required value="{{ old('sekolah', $profile->sekolah ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pengalaman" role="tabpanel" aria-labelledby="pengalaman-tab">

                                <div id="pengalaman-list">
                                    @forelse ($profile->workExperiences ?? [] as $i => $exp)
                                        <div class="pengalaman-item mb-4" data-index="{{ $i + 1 }}">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold mb-0 pengalaman-title">Perusahaan {{ $i + 1 }}
                                                </h6>
                                                <button type="button"
                                                    class="btn btn-sm pf-btn-ghost btn-hapus-pengalaman {{ $loop->count <= 1 ? 'd-none' : '' }}">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="pengalaman[{{ $i + 1 }}][perusahaan]"
                                                        placeholder="Nama Perusahaan" value="{{ $exp->perusahaan }}">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="pengalaman[{{ $i + 1 }}][posisi]"
                                                        placeholder="Posisi/Jabatan" value="{{ $exp->posisi }}">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="pengalaman[{{ $i + 1 }}][kota]" placeholder="Kota"
                                                        value="{{ $exp->kota }}">
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">Mulai Kerja</label>
                                                    <input type="date" class="form-control"
                                                        name="pengalaman[{{ $i + 1 }}][mulai_kerja]"
                                                        value="{{ optional($exp->mulai_kerja)->format('Y-m-d') }}">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">Berhenti Kerja</label>
                                                    <input type="date" class="form-control input-berhenti-kerja"
                                                        name="pengalaman[{{ $i + 1 }}][berhenti_kerja]"
                                                        value="{{ optional($exp->berhenti_kerja)->format('Y-m-d') }}"
                                                        {{ $exp->masih_bekerja == '1' ? 'disabled' : '' }}>
                                                </div>
                                                <div class="col-12 col-md-4 d-flex align-items-center">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input checkbox-masih-bekerja"
                                                            type="checkbox" id="masih_bekerja_{{ $i + 1 }}"
                                                            name="pengalaman[{{ $i + 1 }}][masih_bekerja]"
                                                            value="1"
                                                            {{ $exp->masih_bekerja == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="masih_bekerja_{{ $i + 1 }}">
                                                            Masih Bekerja Di Sini
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Tanggung Jawab Pekerjaan</label>
                                                    <textarea class="form-control" name="pengalaman[{{ $i + 1 }}][tanggung_jawab]" rows="3">{{ $exp->tanggung_jawab }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="pengalaman-item mb-4" data-index="1">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold mb-0 pengalaman-title">Perusahaan 1</h6>
                                                <button type="button"
                                                    class="btn btn-sm pf-btn-ghost btn-hapus-pengalaman d-none">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="pengalaman[1][perusahaan]" placeholder="Nama Perusahaan">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="pengalaman[1][posisi]" placeholder="Posisi/Jabatan">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control" name="pengalaman[1][kota]"
                                                        placeholder="Kota">
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">Mulai Kerja</label>
                                                    <input type="date" class="form-control"
                                                        name="pengalaman[1][mulai_kerja]">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">Berhenti Kerja</label>
                                                    <input type="date" class="form-control input-berhenti-kerja"
                                                        name="pengalaman[1][berhenti_kerja]">
                                                </div>
                                                <div class="col-12 col-md-4 d-flex align-items-center">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input checkbox-masih-bekerja"
                                                            type="checkbox" id="masih_bekerja_1"
                                                            name="pengalaman[1][masih_bekerja]" value="1">
                                                        <label class="form-check-label" for="masih_bekerja_1">Masih
                                                            Bekerja Di
                                                            Sini</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Tanggung Jawab Pekerjaan</label>
                                                    <textarea class="form-control" name="pengalaman[1][tanggung_jawab]" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <button type="button" class="btn pf-btn-ghost px-3" id="btn-tambah-pengalaman">
                                    <i class="bi bi-plus-lg"></i> Tambah Pengalaman Kerja
                                </button>

                            </div>

                        </div>
                    </div>

                    <div class="pf-footer text-end">
                        <button type="reset" class="btn pf-btn-ghost px-4">Batal</button>
                        <button type="submit" class="btn pf-btn-primary text-white px-4 ms-1">Simpan</button>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </form>
@endsection

@push('js')
    <script>
        // ==== Daftar field yang dihitung untuk Kelengkapan Profil ====
        const requiredFields = [
            'nik', 'nama', 'kelamin', 'tempat_lahir', 'tanggal_lahir',
            'alamat',
            'domisili_provinsi', 'domisili_kabupaten', 'domisili_kecamatan', 'domisili_desa',
            'no_hp', 'email', 'agama', 'status', 'bpjs', 'npwp',
            'pendidikan', 'jurusan', 'sekolah'
        ];

        let fotoUploaded = {{ $profile && $profile->foto ? 'true' : 'false' }};
        let cvUploaded = {{ $profile && $profile->cv ? 'true' : 'false' }};
        const defaultPhoto = "{{ asset('dashboard/img/logo.png') }}";

        function hitungKelengkapanProfil() {
            let totalItem = requiredFields.length + 2; // +2 untuk foto & cv
            let terisi = 0;

            requiredFields.forEach(function(id) {
                const el = document.getElementById(id);
                if (el && el.value && el.value.trim() !== '') {
                    terisi++;
                }
            });

            if (fotoUploaded) terisi++;
            if (cvUploaded) terisi++;

            const percent = Math.round((terisi / totalItem) * 100);

            document.getElementById('completion-percent').textContent = percent + '%';
            document.getElementById('completion-bar').style.width = percent + '%';

            // badge upload foto & CV tetap dihitung terpisah (khusus 2 item ini)
            const uploadCount = (fotoUploaded ? 1 : 0) + (cvUploaded ? 1 : 0);
            document.getElementById('upload-count').textContent = uploadCount;
        }

        function handleFotoChange(event) {
            const file = event.target.files[0];
            const avatar = document.getElementById('header-avatar-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatar.src = e.target.result;
                };
                reader.readAsDataURL(file);
                fotoUploaded = true;
            } else {
                avatar.src = defaultPhoto;
                fotoUploaded = false;
            }

            hitungKelengkapanProfil();
        }

        function handleCvChange(event) {
            cvUploaded = !!event.target.files[0];
            hitungKelengkapanProfil();
        }

        // Pasang listener ke semua field data pribadi & pendidikan
        document.addEventListener('DOMContentLoaded', function() {
            requiredFields.forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', hitungKelengkapanProfil);
                    el.addEventListener('change', hitungKelengkapanProfil); // untuk <select>
                }
            });

            hitungKelengkapanProfil(); // hitung sekali di awal (misal email sudah terisi otomatis)
        });
    </script>
    <script>
        let pengalamanIndex =
            {{ $profile && $profile->workExperiences->count() > 0 ? $profile->workExperiences->count() : 1 }};

        document.getElementById('btn-tambah-pengalaman').addEventListener('click', function() {
            pengalamanIndex++;

            const template = `
            <div class="pengalaman-item mb-4" data-index="${pengalamanIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 pengalaman-title">Perusahaan ${pengalamanIndex}</h6>
                    <button type="button" class="btn btn-sm pf-btn-ghost btn-hapus-pengalaman">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control" name="pengalaman[${pengalamanIndex}][perusahaan]"
                               placeholder="Nama Perusahaan">
                    </div>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control" name="pengalaman[${pengalamanIndex}][posisi]"
                               placeholder="Posisi/Jabatan">
                    </div>  
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control" name="pengalaman[${pengalamanIndex}][kota]"
                               placeholder="Kota">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Mulai Kerja</label>
                        <input type="date" class="form-control" name="pengalaman[${pengalamanIndex}][mulai_kerja]">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Berhenti Kerja</label>
                        <input type="date" class="form-control input-berhenti-kerja" name="pengalaman[${pengalamanIndex}][berhenti_kerja]">
                    </div>
                    <div class="col-12 col-md-4 d-flex align-items-center">
                        <div class="form-check mb-3">
                            <input class="form-check-input checkbox-masih-bekerja" type="checkbox"
                                   id="masih_bekerja_${pengalamanIndex}" name="pengalaman[${pengalamanIndex}][masih_bekerja]" value="1">
                            <label class="form-check-label" for="masih_bekerja_${pengalamanIndex}">
                                Masih Bekerja Di Sini
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Tanggung Jawab Pekerjaan</label>
                        <textarea class="form-control" name="pengalaman[${pengalamanIndex}][tanggung_jawab]" rows="3"></textarea>
                    </div>
                </div>
            </div>
        `;

            document.getElementById('pengalaman-list').insertAdjacentHTML('beforeend', template);
            toggleTombolHapus();
            pasangEventListenerBaru();
            hitungKelengkapanProfil(); // opsional, jika ingin ikut dihitung ke progress
        });

        function toggleTombolHapus() {
            const items = document.querySelectorAll('.pengalaman-item');
            const tombolHapus = document.querySelectorAll('.btn-hapus-pengalaman');

            // tombol hapus disembunyikan kalau cuma ada 1 blok
            tombolHapus.forEach(function(btn) {
                btn.classList.toggle('d-none', items.length <= 1);
            });
        }

        function pasangEventListenerBaru() {
            // Hapus blok pengalaman
            document.querySelectorAll('.btn-hapus-pengalaman').forEach(function(btn) {
                btn.onclick = function() {
                    btn.closest('.pengalaman-item').remove();
                    renumberPengalaman();
                    toggleTombolHapus();
                };
            });

            document.querySelectorAll('.checkbox-masih-bekerja').forEach(function(checkbox) {
                checkbox.onchange = function() {
                    const inputBerhenti = checkbox.closest('.pengalaman-item').querySelector(
                        '.input-berhenti-kerja');
                    if (checkbox.checked) {
                        inputBerhenti.value = '';
                        inputBerhenti.disabled = true;
                    } else {
                        inputBerhenti.disabled = false;
                    }
                };
            });
        }

        function renumberPengalaman() {
            document.querySelectorAll('.pengalaman-item').forEach(function(item, i) {
                item.querySelector('.pengalaman-title').textContent = 'Perusahaan ' + (i + 1);
            });
        }

        // Pasang listener untuk blok pertama saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            toggleTombolHapus();
            pasangEventListenerBaru();
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
    <script>
        $(function() {
            const $prov = $('#domisili_provinsi');
            const $kab = $('#domisili_kabupaten');
            const $kec = $('#domisili_kecamatan');
            const $desa = $('#domisili_desa');

            const selectedProv = $prov.data('selected');
            const selectedKab = $kab.data('selected');
            const selectedKec = $kec.data('selected');
            const selectedDesa = $desa.data('selected');

            function fillSelect($el, data, selectedValue = null) {
                let options = '<option value="">Pilih</option>';
                data.forEach(d => {
                    const isSelected = selectedValue && String(d.code) === String(selectedValue) ?
                        'selected' : '';
                    options += `<option value="${d.code}" ${isSelected}>${d.name}</option>`;
                });
                $el.html(options);
            }

            function resetSelect(selectors) {
                selectors.forEach(s => {
                    $(s).html('<option value="">Pilih</option>').prop('disabled', true);
                });
            }

            // Helper: panggil ulang hitung kelengkapan profil kalau fungsinya ada
            function recalcKelengkapan() {
                if (typeof hitungKelengkapanProfil === 'function') {
                    hitungKelengkapanProfil();
                }
            }

            $.get('{{ route('wilayah.provinces') }}', function(res) {
                fillSelect($prov, res, selectedProv);

                if (selectedProv) {
                    loadKabupaten(selectedProv, selectedKab, function() {
                        if (selectedKab) {
                            loadKecamatan(selectedKab, selectedKec, function() {
                                if (selectedKec) {
                                    loadDesa(selectedKec, selectedDesa, recalcKelengkapan);
                                } else {
                                    recalcKelengkapan();
                                }
                            });
                        } else {
                            recalcKelengkapan();
                        }
                    });
                } else {
                    recalcKelengkapan();
                }
            });

            function loadKabupaten(provCode, selected, callback) {
                $.get('{{ url('wilayah/cities') }}/' + provCode, function(res) {
                    fillSelect($kab, res, selected);
                    $kab.prop('disabled', false);
                    if (callback) callback();
                });
            }

            function loadKecamatan(kabCode, selected, callback) {
                $.get('{{ url('wilayah/districts') }}/' + kabCode, function(res) {
                    fillSelect($kec, res, selected);
                    $kec.prop('disabled', false);
                    if (callback) callback();
                });
            }

            function loadDesa(kecCode, selected, callback) {
                $.get('{{ url('wilayah/villages') }}/' + kecCode, function(res) {
                    fillSelect($desa, res, selected);
                    $desa.prop('disabled', false);
                    if (callback) callback();
                });
            }

            $prov.on('change', function() {
                let code = $(this).val();
                resetSelect(['#domisili_kabupaten', '#domisili_kecamatan', '#domisili_desa']);
                if (!code) return;
                loadKabupaten(code, null);
            });

            $kab.on('change', function() {
                let code = $(this).val();
                resetSelect(['#domisili_kecamatan', '#domisili_desa']);
                if (!code) return;
                loadKecamatan(code, null);
            });

            $kec.on('change', function() {
                let code = $(this).val();
                resetSelect(['#domisili_desa']);
                if (!code) return;
                loadDesa(code, null);
            });
        });
    </script>
@endpush
