@extends('admin.layouts.master')

@section('tittle')
    Pengaturan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"> <a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8">
                <div class="card">

                    <form action="{{ route('admin.setting.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($setting)
                            <input type="hidden" name="id" value="{{ $setting->id }}">
                        @endif

                        <div class="card-body">

                            <div class="form-group mb-3">
                                <label>Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    placeholder="Contoh: PT Prakarsa Alam Segar"
                                    value="{{ old('nama', $setting->nama ?? '') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                                    placeholder="Alamat lengkap perusahaan" required>{{ old('alamat', $setting->alamat ?? '') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="info@perusahaan.com"
                                            value="{{ old('email', $setting->email ?? '') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>No. HP / Telepon <span class="text-danger">*</span></label>
                                        <input type="text" name="nohp"
                                            class="form-control @error('nohp') is-invalid @enderror"
                                            placeholder="081234567890" value="{{ old('nohp', $setting->nohp ?? '') }}"
                                            required>
                                        @error('nohp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mb-3">
                                <label>Banner Teks 1 <span class="text-danger">*</span></label>
                                <textarea name="teks1" rows="2" class="form-control @error('teks1') is-invalid @enderror" required>{{ old('teks1', $setting->teks1 ?? '') }}</textarea>
                                @error('teks1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Banner Teks 2 <span class="text-danger">*</span></label>
                                <textarea name="teks2" rows="2" class="form-control @error('teks2') is-invalid @enderror" required>{{ old('teks2', $setting->teks2 ?? '') }}</textarea>
                                @error('teks2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer bg-white text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Preview / info tambahan --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-circle-info text-primary me-1"></i> Informasi
                        </h6>
                        <p class="small text-muted mb-2">
                            Data pada halaman ini digunakan untuk ditampilkan di halaman publik, seperti footer, kontak, dan
                            informasi perusahaan.
                        </p>
                        @if ($setting)
                            <hr>
                            <p class="small text-muted mb-1">
                                <i class="far fa-clock me-1"></i> Terakhir diperbarui:
                            </p>
                            <p class="small fw-medium mb-0">
                                {{ formatTanggalIndo($setting->updated_at) }} WIB
                            </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
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
