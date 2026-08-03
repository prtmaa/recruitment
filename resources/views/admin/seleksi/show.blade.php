@extends('admin.layouts.master')

@section('tittle')
    Data Pelamar
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.seleksi.index') }}">Lamaran</a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h5 class="fw-semibold mb-1">{{ $job->judul }}</h5>

            </div>
            <p class="text-muted">Total pelamar: {{ $applications->total() }}</p>
        </div>

        {{-- Filter --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / NIK..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach (['pending' => 'Menunggu', 'review' => 'Review', 'interview' => 'Interview', 'accepted' => 'Diterima', 'rejected' => 'Ditolak'] as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pelamar</th>
                            <th>Pendidikan</th>
                            <th>Tanggal Melamar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $app)
                            @php
                                $statusConfig = [
                                    'pending' => ['label' => 'Menunggu', 'class' => 'secondary'],
                                    'review' => ['label' => 'Review', 'class' => 'info'],
                                    'interview' => ['label' => 'Interview', 'class' => 'warning'],
                                    'accepted' => ['label' => 'Diterima', 'class' => 'success'],
                                    'rejected' => ['label' => 'Ditolak', 'class' => 'danger'],
                                ];
                                $config = $statusConfig[$app->status];
                                $profile = $app->applicantProfile;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $profile->foto ? asset('storage/' . $profile->foto) : asset('images/default-avatar.png') }}"
                                            class="rounded-circle mr-3" width="36" height="36"
                                            style="object-fit:cover">
                                        <div>
                                            <span class="fw-semibold d-block">{{ $profile->nama }}</span>
                                            <small class="text-muted">{{ $profile->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $profile->pendidikan }} @if ($profile->jurusan)
                                        - {{ $profile->jurusan }}
                                    @endif
                                </td>
                                <td>{{ $app->tanggal_melamar->translatedFormat('d M Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $config['class'] }}">{{ $config['label'] }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-warning" title="Lihat Detail"
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $app->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button class="btn btn-sm btn-info" title="Ubah Status" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $app->id }}">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            @if ($app->status === 'interview' && $profile->no_hp_wa && $job->interviewSchedule)
                                                @php
                                                    $jadwal = $job->interviewSchedule;
                                                    $pesanWa =
                                                        "Halo {$profile->nama},\n\n" .
                                                        "Selamat! Anda dipanggil untuk mengikuti *interview* pada tahap seleksi posisi *{$job->judul}* di PT Widodo Makmur Unggas.\n\n" .
                                                        'Tanggal: ' .
                                                        formatTanggalIndo($jadwal->tanggal_interview) .
                                                        "\n" .
                                                        'Waktu: ' .
                                                        \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') .
                                                        ($jadwal->jam_selesai
                                                            ? ' - ' .
                                                                \Carbon\Carbon::parse($jadwal->jam_selesai)->format(
                                                                    'H:i',
                                                                )
                                                            : '') .
                                                        " WIB\n" .
                                                        "Tempat: {$jadwal->tempat}\n" .
                                                        ($jadwal->catatan ? "\nCatatan: {$jadwal->catatan}\n" : '') .
                                                        "\nMohon konfirmasi kehadiran Anda melalui pesan ini.\n\nTerima kasih.";
                                                @endphp
                                                <a href="https://wa.me/{{ $profile->no_hp_wa }}?text={{ urlencode($pesanWa) }}"
                                                    target="_blank" class="btn btn-sm btn-success"
                                                    title="Kirim Panggilan Interview via WA">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @elseif ($app->status === 'interview' && !$job->interviewSchedule)
                                                <button class="btn btn-sm btn-outline-secondary"
                                                    title="Isi jadwal interview di halaman Data Loker dulu" disabled>
                                                    <i class="fab fa-whatsapp"></i>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada pelamar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($applications->hasPages())
                <div class="card-footer bg-white">
                    {{ $applications->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

        @include('admin.seleksi.form')
        @include('admin.seleksi.detail')
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#8e1a25',
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    @endif
@endpush
