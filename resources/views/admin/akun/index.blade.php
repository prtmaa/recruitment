@extends('admin.layouts.master')

@section('tittle')
    Data Akun Pelamar
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Akun</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body table-responsive">
                        <table class="table text-center table-bordered" id="table-user">
                            <thead>
                                <th style="width: 20px;">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Status Profil</th>
                                <th>Bergabung</th>
                                <th style="width: 100px;">Aksi</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('admin.akun.detail')
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let table;
        $(function() {
            table = $('#table-user').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                autoWidth: false,
                responsive: true,
                "language": {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sSearch": "Pencarian:",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    },
                },
                ajax: {
                    url: '{{ route('admin.akun.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp'
                    },
                    {
                        data: 'profil',
                        name: 'profil',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bergabung',
                        name: 'bergabung'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ],
            });
        });

        function deleteData(url) {
            Swal.fire({
                title: 'Yakin?',
                text: "Akun beserta seluruh data profil dan riwayat lamarannya akan dihapus permanen",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            _token: '{{ csrf_token() }}',
                            _method: 'delete'
                        })
                        .done((response) => {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .fail(() => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'Data gagal dihapus'
                            });
                        });
                }
            });
        }
    </script>
    <script>
        function lihatDetail(url) {
            $('#modal-detail-user').modal('show');
            $('#detail-user-content').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="text-muted small mt-2 mb-0">Memuat data...</p>
        </div>
    `);

            $.get(url)
                .done((user) => {
                    const profile = user.profile;

                    if (!profile) {
                        $('#detail-user-content').html(`
                    <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Pelamar belum melengkapi profil.</p>
                    </div>
                `);
                        return;
                    }

                    const statusConfig = {
                        pending: {
                            label: 'Menunggu',
                            class: 'secondary',
                            icon: 'fa-hourglass-half'
                        },
                        review: {
                            label: 'Review',
                            class: 'info',
                            icon: 'fa-eye'
                        },
                        interview: {
                            label: 'Interview',
                            class: 'warning',
                            icon: 'fa-comments'
                        },
                        accepted: {
                            label: 'Diterima',
                            class: 'success',
                            icon: 'fa-check'
                        },
                        rejected: {
                            label: 'Ditolak',
                            class: 'danger',
                            icon: 'fa-xmark'
                        },
                    };

                    // Pengalaman kerja
                    let expHtml = '';
                    if (profile.work_experiences && profile.work_experiences.length > 0) {
                        profile.work_experiences.forEach(exp => {
                            const periode = (exp.mulai_kerja ? formatBulanTahun(exp.mulai_kerja) : '-') +
                                ' - ' +
                                (exp.masih_bekerja == '1' ? 'Sekarang' : (exp.berhenti_kerja ? formatBulanTahun(
                                    exp.berhenti_kerja) : '-'));

                            expHtml += `
                        <div class="border rounded-3 p-3 mb-2">
                            <div class="d-flex justify-content-between flex-wrap gap-1">
                                <span class="fw-semibold">${exp.posisi} — ${exp.perusahaan}</span>
                                ${exp.masih_bekerja == '1' ? '<span class="badge text-bg-success">Masih Bekerja</span>' : ''}
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-map-marker-alt me-1 mr-1"></i>${exp.kota} &middot; ${periode}
                            </small>
                            ${exp.tanggung_jawab ? `<p class="mb-0 small">${exp.tanggung_jawab}</p>` : ''}
                        </div>
                    `;
                        });
                    } else {
                        expHtml = '<p class="text-muted small">Belum ada pengalaman kerja yang dicantumkan.</p>';
                    }

                    // Riwayat lamaran
                    let riwayatHtml = '';
                    if (profile.applications && profile.applications.length > 0) {
                        let rows = '';
                        profile.applications.forEach(riwayat => {
                            const cfg = statusConfig[riwayat.status] || statusConfig.pending;
                            rows += `
                        <tr>
                            <td>${riwayat.job ? riwayat.job.judul : '-'}</td>
                            <td><small>${formatTanggal(riwayat.tanggal_melamar)}</small></td>
                            <td><span class="badge text-bg-${cfg.class}"><i class="fas ${cfg.icon} me-1 mr-1"></i>${cfg.label}</span></td>
                        </tr>
                    `;
                        });

                        riwayatHtml = `
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>Posisi</th><th>Tanggal Melamar</th><th>Progres</th></tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
                    } else {
                        riwayatHtml = '<p class="text-muted small mb-0">Belum ada riwayat lamaran.</p>';
                    }

                    const fotoUrl = profile.foto ? `${BASE_STORAGE_URL}/${profile.foto}` :
                        `${BASE_STORAGE_URL}/../images/default-avatar.png`;
                    const cvHtml = profile.cv ?
                        `<a href="${BASE_STORAGE_URL}/${profile.cv}" target="_blank" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> Lihat</a>` :
                        '<p class="text-muted small mb-0">Dokumen belum diunggah.</p>';

                    $('#detail-user-content').html(`
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <img src="${fotoUrl}" class="rounded-circle mr-3" width="70" height="70" style="object-fit:cover">
                    <div>
                        <h5 class="mb-1 fw-semibold">${profile.nama}</h5>
                        <small class="text-muted d-block">${user.email}</small>
                        <small class="text-muted"><i class="fas fa-phone me-1 mr-1"></i>${profile.no_hp ?? '-'}</small>
                    </div>
                </div>

                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-id-card me-1"></i> Data Diri</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">NIK</small><span class="fw-medium">${profile.nik ?? '-'}</span></div>
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">Jenis Kelamin</small><span class="fw-medium">${profile.kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</span></div>
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">Tempat, Tanggal Lahir</small><span class="fw-medium">${profile.tempat_lahir ?? '-'}, ${profile.tanggal_lahir ? formatTanggal(profile.tanggal_lahir) : '-'}</span></div>
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">Agama</small><span class="fw-medium">${profile.agama ?? '-'}</span></div>
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">Status Pernikahan</small><span class="fw-medium">${profile.status ?? '-'}</span></div>
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">BPJS</small><span class="fw-medium">${profile.bpjs ?? '-'}</span></div>
                    <div class="col-md-6 mb-2"><small class="text-muted d-block">NPWP</small><span class="fw-medium">${profile.npwp ?? '-'}</span></div>
                    <div class="col-12 mb-2"><small class="text-muted d-block">Alamat KTP</small><span class="fw-medium">${profile.alamat ?? '-'}</span></div>
                    <div class="col-12 mb-2"><small class="text-muted d-block">Domisili</small><span class="fw-medium">${profile.domisili_lengkap ?? '-'}</span></div>
                </div>

                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-graduation-cap me-1"></i> Pendidikan</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><small class="text-muted d-block">Jenjang</small><span class="fw-medium">${profile.pendidikan ?? '-'}</span></div>
                    <div class="col-md-4"><small class="text-muted d-block">Jurusan</small><span class="fw-medium">${profile.jurusan ?? '-'}</span></div>
                    <div class="col-md-4"><small class="text-muted d-block">Asal Sekolah/Kampus</small><span class="fw-medium">${profile.sekolah ?? '-'}</span></div>
                </div>

                <h6 class="fw-semibold text-primary mb-3"><i class="fas fa-briefcase me-1"></i> Pengalaman Kerja</h6>
                ${expHtml}

                <h6 class="fw-semibold text-primary mb-3 mt-4"><i class="fas fa-file-alt me-1"></i> Dokumen</h6>
                ${cvHtml}

                <h6 class="fw-semibold text-primary mb-3 mt-4"><i class="fas fa-history me-1"></i> Riwayat Lamaran</h6>
                ${riwayatHtml}
            `);
                })
                .fail(() => {
                    $('#detail-user-content').html(`
                <div class="text-center py-5 text-danger">
                    <i class="fas fa-circle-exclamation fa-2x mb-2"></i>
                    <p class="mb-0">Gagal memuat data pelamar.</p>
                </div>
            `);
                });
        }

        function formatTanggal(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function formatBulanTahun(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', {
                month: 'short',
                year: 'numeric'
            });
        }
    </script>
@endpush
