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
                    <div class="col-md-auto">
                        <button type="button" class="btn btn-light border" data-bs-toggle="modal"
                            data-bs-target="#statusFlowModal" title="Panduan alur status">
                            <i class="fas fa-info-circle" style="font-size: 1.15rem;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-pelamar" class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pelamar</th>
                                <th class="text-center">Pendidikan</th>
                                <th class="text-center">Tanggal Melamar</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $app)
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
                                    <td class="text-center">
                                        {{ $profile->pendidikan }} @if ($profile->jurusan)
                                            - {{ $profile->jurusan }}
                                        @endif
                                    </td>
                                    <td class="text-center" data-order="{{ $app->tanggal_melamar->format('Y-m-d') }}">
                                        {{ $app->tanggal_melamar->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="text-center" id="status-cell-{{ $app->id }}">
                                        <span class="badge badge-{{ $config['class'] }}">{{ $config['label'] }}</span>
                                    </td>
                                    <td class="text-center" id="aksi-cell-{{ $app->id }}">
                                        @include('admin.seleksi.aksi', [
                                            'app' => $app,
                                            'job' => $job,
                                            'profile' => $profile,
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('admin.seleksi.form')
        @include('admin.seleksi.detail')
        @include('admin.seleksi.info')
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let dtPelamar;

        // Aturan transisi status yang diizinkan (termasuk status saat ini sendiri)
        const allowedTransitions = {
            pending: ['pending', 'review', 'interview'],
            review: ['review', 'interview', 'rejected'],
            interview: ['interview', 'accepted', 'rejected'],
            accepted: ['accepted'],
            rejected: ['rejected'],
        };

        // Sumber kebenaran status per aplikasi — diisi awal dari data-current,
        // lalu diupdate tiap kali AJAX sukses. JANGAN andalkan $.data() DOM lagi.
        const currentStatusMap = {};

        $(function() {
            // Inisialisasi map dari atribut data-current yang dirender server
            $('.select-status').each(function() {
                const $s = $(this);
                const appId = $s.data('app-id');
                currentStatusMap[appId] = $s.data('current');
            });

            dtPelamar = $('#tabel-pelamar').DataTable({
                stateSave: true,
                stateDuration: -1,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                pageLength: 10,
                order: [
                    [2, 'desc']
                ],
                "language": {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    },
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                searching: false
            });
        });

        // Saat modal ubah status dibuka: disable opsi yang tidak diizinkan
        // berdasarkan currentStatusMap (bukan atribut DOM)
        $(document).on('shown.bs.modal', '[id^=statusModal]', function() {
            const $select = $(this).find('.select-status');
            const appId = $select.data('app-id');
            const current = currentStatusMap[appId];
            const allowed = allowedTransitions[current] || [current];

            $select.find('option').each(function() {
                const val = $(this).val();
                $(this).prop('disabled', !allowed.includes(val));
                $(this).prop('selected', val === current);
            });
        });

        // Handle submit form ubah status via AJAX
        $(document).on('submit', '.form-update-status', function(e) {
            e.preventDefault();

            const $form = $(this);
            const appId = $form.data('app-id');
            const $submitBtn = $form.find('button[type=submit]');
            const originalText = $submitBtn.text();

            $submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(res) {
                    // Update sel status & aksi tanpa reload
                    $('#status-cell-' + appId).html(res.badge_html);
                    $('#aksi-cell-' + appId).html(res.aksi_html);

                    const $row = $('#status-cell-' + appId).closest('tr');
                    dtPelamar.row($row).invalidate().draw(false);

                    // Update SATU-SATUNYA sumber kebenaran status
                    currentStatusMap[appId] = res.new_status;

                    // Sinkronkan juga atribut DOM biar konsisten kalau ada kode lain yang baca
                    $('#statusModal' + appId + ' .select-status')
                        .attr('data-current', res.new_status)
                        .data('current', res.new_status);

                    $('#statusModal' + appId).modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        confirmButtonColor: '#8e1a25',
                        timer: 1500,
                        timerProgressBar: true
                    });
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan, silakan coba lagi.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        } else if (xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg,
                        confirmButtonColor: '#8e1a25'
                    });
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
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
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    @endif
@endpush
