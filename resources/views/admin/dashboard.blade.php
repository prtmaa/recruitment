@extends('admin.layouts.master')

@section('tittle')
    Dashboard Admin
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Dashboard</a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success mr-2">
                            <i class="fas fa-briefcase "></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $lowonganAktif }}</h4>
                            <small class="text-muted">Lowongan Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-info bg-opacity-10 text-info mr-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $totalPelamar }}</h4>
                            <small class="text-muted">Total User</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary mr-2">
                            <i class="fas fa-file"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $totalLamaran }}</h4>
                            <small class="text-muted">Total Lamaran</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger mr-2">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $ringkasanStatus['pending'] }}</h4>
                            <small class="text-muted">Menunggu Diproses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">

            {{-- Grafik lamaran masuk --}}
            <div class="col-12 col-lg-8 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white">
                        <h6 class="fw-semibold mb-0">Lamaran Masuk (7 Hari Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartLamaran" height="100"></canvas>
                    </div>
                </div>
            </div>

            {{-- Distribusi status --}}
            <div class="col-12 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white">
                        <h6 class="fw-semibold mb-0">Distribusi Status</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartStatus" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- Persebaran Wilayah Pelamar --}}
            <div class="col-12 col-lg-12 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0"><i class="fas fa-map-marked-alt text-primary me-1"></i> Persebaran
                            Wilayah Pelamar</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartWilayah" height="120"></canvas>
                    </div>
                </div>
            </div>

            {{-- Lowongan segera tutup --}}
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0">
                            <i class="fas fa-clock text-primary me-1"></i> Lowongan Segera Tutup
                        </h6>
                        <a href="{{ route('admin.job.index') }}" class="small">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($lowonganSegeraTutup as $job)
                            <div
                                class="d-flex justify-content-between align-items-center px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <span class="fw-semibold d-block">{{ $job->judul }}</span>
                                    <small class="text-muted">
                                        <i
                                            class="far fa-calendar-alt me-1 mr-1"></i>{{ formatTanggalIndo($job->tanggal_tutup) }}
                                        &middot; {{ $job->applications_count }} pelamar
                                    </small>
                                </div>
                                <a href="{{ route('admin.seleksi.show', $job->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0 small">Tidak ada lowongan yang segera tutup.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Lamaran perlu perhatian --}}
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0">
                            <i class="fas fa-info-circle text-primary me-1"></i> Perlu Ditinjau
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($lamaranPerluPerhatian as $item)
                            <div
                                class="d-flex justify-content-between align-items-center px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <span class="fw-semibold d-block">{{ $item->applicantProfile->nama ?? '-' }}</span>
                                    <small class="text-muted">
                                        {{ $item->job->judul ?? '-' }} &middot;
                                        {{ $item->tanggal_melamar->diffForHumans() }}
                                    </small>
                                </div>
                                <a href="{{ route('admin.seleksi.show', $item->job_id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0 small">Semua lamaran sudah ditinjau. 🎉</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Lamaran terbaru --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white">
                        <h6 class="fw-semibold mb-0"><i class="fas fa-chart-line text-primary me-1"></i> Lamaran Terbaru
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pelamar</th>
                                        <th>Posisi</th>
                                        <th>Tanggal Melamar</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['label' => 'Menunggu', 'class' => 'secondary'],
                                            'review' => ['label' => 'Review', 'class' => 'info'],
                                            'interview' => ['label' => 'Interview', 'class' => 'warning'],
                                            'accepted' => ['label' => 'Diterima', 'class' => 'success'],
                                            'rejected' => ['label' => 'Ditolak', 'class' => 'danger'],
                                        ];
                                    @endphp
                                    @forelse ($lamaranTerbaru as $item)
                                        @php $cfg = $statusConfig[$item->status] ?? $statusConfig['pending']; @endphp
                                        <tr>
                                            <td>{{ $item->applicantProfile->nama ?? '-' }}</td>
                                            <td>{{ $item->job->judul ?? '-' }}</td>
                                            <td><small
                                                    class="text-muted">{{ $item->tanggal_melamar->translatedFormat('d M Y, H:i') }}</small>
                                            </td>
                                            <td><span class="badge badge-{{ $cfg['class'] }}">{{ $cfg['label'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.seleksi.show', $item->job_id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Belum ada lamaran
                                                masuk.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pencarian NIK --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white">
                        <h6 class="fw-semibold mb-0"><i class="fas fa-search text-primary me-1"></i> Cari Pelamar
                            Berdasarkan NIK</h6>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-4" style="max-width: 400px;">
                            <input type="text" id="inputNik" class="form-control" placeholder="Masukkan NIK...">
                            <button class="btn btn-primary" id="btnCariNik" type="button">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>

                        <div id="hasilNikKosong" class="text-muted small d-none">Data tidak ditemukan.</div>

                        <div id="hasilNik" class="d-none">

                            {{-- Header profil --}}
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <img id="nikFoto" src="" class="rounded-circle mr-3" width="70"
                                    height="70" style="object-fit:cover">
                                <div>
                                    <h5 class="mb-1 fw-semibold" id="nikNama">-</h5>
                                    <small class="text-muted d-block" id="nikEmail">-</small>
                                    <small class="text-muted"><i class="fas fa-phone me-1 mr-1"></i><span
                                            id="nikTelepon">-</span></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    {{-- Data Diri --}}
                                    <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-id-card me-1"></i> Data
                                        Diri
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-7 mb-2">
                                            <small class="text-muted d-block">NIK</small>
                                            <span class="fw-medium" id="nikNikVal">-</span>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <small class="text-muted d-block">Jenis Kelamin</small>
                                            <span class="fw-medium" id="nikKelamin">-</span>
                                        </div>
                                        <div class="col-md-7 mb-2">
                                            <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                                            <span class="fw-medium" id="nikLahir">-</span>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <small class="text-muted d-block">Agama</small>
                                            <span class="fw-medium" id="nikAgama">-</span>
                                        </div>
                                        <div class="col-md-7 mb-2">
                                            <small class="text-muted d-block">Status Pernikahan</small>
                                            <span class="fw-medium" id="nikStatus">-</span>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <small class="text-muted d-block">BPJS</small>
                                            <span class="fw-medium" id="nikBpjs">-</span>
                                        </div>
                                        <div class="col-md-7 mb-2">
                                            <small class="text-muted d-block">NPWP</small>
                                            <span class="fw-medium" id="nikNpwp">-</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <small class="text-muted d-block">Alamat KTP</small>
                                            <span class="fw-medium" id="nikAlamat">-</span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block">Domisili</small>
                                            <span class="fw-medium" id="nikDomisili">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {{-- Pendidikan --}}
                                    <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-graduation-cap me-1"></i>
                                        Pendidikan</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-12 mb-2">
                                            <small class="text-muted d-block">Jenjang</small>
                                            <span class="fw-medium" id="nikPendidikan">-</span>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <small class="text-muted d-block">Jurusan</small>
                                            <span class="fw-medium" id="nikJurusan">-</span>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <small class="text-muted d-block">Asal Sekolah/Kampus</small>
                                            <span class="fw-medium" id="nikSekolah">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {{-- Pengalaman Kerja --}}
                                    <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-briefcase me-1"></i>
                                        Pengalaman
                                        Kerja</h6>
                                    <div id="nikPengalamanBody" class="mb-4"></div>
                                </div>

                                <div class="col-md-2">
                                    {{-- CV --}}
                                    <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-file-alt me-1"></i> Dokumen
                                    </h6>
                                    <div id="nikCvBody" class="mb-4"></div>
                                </div>
                            </div>


                            {{-- Riwayat Lamaran --}}
                            <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-history me-1"></i> Riwayat
                                Lamaran</h6>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Posisi</th>
                                            <th>Tanggal Melamar</th>
                                            <th>Progres</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="nikRiwayatBody"></tbody>
                                </table>
                            </div>

                        </div>
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

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
    </style>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Grafik lamaran masuk (line chart)
        new Chart(document.getElementById('chartLamaran'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Lamaran Masuk',
                    data: @json($chartData),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Grafik distribusi status (doughnut chart)
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Review', 'Interview', 'Diterima', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ $ringkasanStatus['pending'] }},
                        {{ $ringkasanStatus['review'] }},
                        {{ $ringkasanStatus['interview'] }},
                        {{ $ringkasanStatus['accepted'] }},
                        {{ $ringkasanStatus['rejected'] }},
                    ],
                    backgroundColor: ['#6c757d', '#0dcaf0', '#ffc107', '#198754', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        document.getElementById('btnCariNik').addEventListener('click', cariNik);
        document.getElementById('inputNik').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') cariNik();
        });

        function cariNik() {
            const nik = document.getElementById('inputNik').value.trim();
            const hasilNik = document.getElementById('hasilNik');
            const hasilKosong = document.getElementById('hasilNikKosong');

            if (!nik) return;

            hasilNik.classList.add('d-none');
            hasilKosong.classList.add('d-none');

            fetch(`{{ route('admin.dashboard.cariNik') }}?nik=${encodeURIComponent(nik)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.found) {
                        hasilKosong.textContent = data.message || 'Data tidak ditemukan.';
                        hasilKosong.classList.remove('d-none');
                        return;
                    }

                    const p = data.profile;

                    document.getElementById('nikFoto').src = p.foto;
                    document.getElementById('nikNama').textContent = p.nama || '-';
                    document.getElementById('nikEmail').textContent = p.email || '-';
                    document.getElementById('nikTelepon').textContent = p.no_hp || '-';

                    document.getElementById('nikNikVal').textContent = p.nik || '-';
                    document.getElementById('nikKelamin').textContent = p.kelamin || '-';
                    document.getElementById('nikLahir').textContent = `${p.tempat_lahir}, ${p.tanggal_lahir}`;
                    document.getElementById('nikAgama').textContent = p.agama || '-';
                    document.getElementById('nikStatus').textContent = p.status || '-';
                    document.getElementById('nikBpjs').textContent = p.bpjs || '-';
                    document.getElementById('nikNpwp').textContent = p.npwp || '-';
                    document.getElementById('nikAlamat').textContent = p.alamat || '-';
                    document.getElementById('nikDomisili').textContent = p.domisili || '-';

                    document.getElementById('nikPendidikan').textContent = p.pendidikan || '-';
                    document.getElementById('nikJurusan').textContent = p.jurusan || '-';
                    document.getElementById('nikSekolah').textContent = p.sekolah || '-';

                    // Pengalaman kerja
                    const pengalamanBody = document.getElementById('nikPengalamanBody');
                    pengalamanBody.innerHTML = '';
                    if (data.pengalaman.length === 0) {
                        pengalamanBody.innerHTML =
                            '<p class="text-muted small">Belum ada pengalaman kerja yang dicantumkan.</p>';
                    } else {
                        data.pengalaman.forEach(exp => {
                            const badge = exp.masih_bekerja ?
                                '<span class="badge text-bg-success">Masih Bekerja</span>' : '';
                            const periode = exp.masih_bekerja ? 'Sekarang' : exp.berhenti_kerja;
                            pengalamanBody.innerHTML += `
                        <div class="border rounded-3 p-3 mb-2">
                            <div class="d-flex justify-content-between flex-wrap gap-1">
                                <span class="fw-semibold">${exp.posisi} — ${exp.perusahaan}</span>
                                ${badge}
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-map-marker-alt me-1 mr-1"></i>${exp.kota || '-'} &middot;
                                ${exp.mulai_kerja} - ${periode}
                            </small>
                            ${exp.tanggung_jawab ? `<p class="mb-0 small">${exp.tanggung_jawab}</p>` : ''}
                        </div>`;
                        });
                    }

                    // CV
                    const cvBody = document.getElementById('nikCvBody');
                    cvBody.innerHTML = p.cv ?
                        `<a href="${p.cv}" target="_blank" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> Lihat</a>` :
                        '<p class="text-muted small mb-0">Dokumen belum diunggah.</p>';

                    // Riwayat lamaran
                    const tbody = document.getElementById('nikRiwayatBody');
                    tbody.innerHTML = '';
                    if (data.riwayat.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat lamaran.</td></tr>';
                    } else {
                        data.riwayat.forEach(r => {
                            tbody.innerHTML += `
                        <tr>
                            <td>${r.posisi}</td>
                            <td><small>${r.tanggal_melamar}</small></td>
                            <td>
                                <span class="badge text-bg-${r.status_class}">
                                    <i class="fas ${r.status_icon} me-1 mr-1"></i>${r.status_label}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="${r.url}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>`;
                        });
                    }

                    hasilNik.classList.remove('d-none');
                })
                .catch(() => {
                    hasilKosong.textContent = 'Terjadi kesalahan saat mencari data.';
                    hasilKosong.classList.remove('d-none');
                });
        }
    </script>
    <script>
        // Grafik persebaran wilayah pelamar (bar chart)
        fetch(`{{ route('admin.dashboard.chartWilayah') }}`)
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('chartWilayah'), {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            label: 'Jumlah Pelamar',
                            data: data.map(d => d.total),
                            backgroundColor: '#0d6efd',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // horizontal bar, lebih enak dibaca kalau nama kabupaten panjang
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
    </script>
@endpush
