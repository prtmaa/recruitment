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

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-semibold mb-1">{{ $job->judul }}</h5>

            </div>
        </div>

        {{-- Filter --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / NIK..."
                            value="{{ request('search') }}">
                    </div>
                    {{-- <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach (['pending' => 'Menunggu', 'review' => 'Review', 'interview' => 'Interview', 'accepted' => 'Diterima', 'rejected' => 'Ditolak'] as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div> --}}
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

            @php
                $statusConfig = [
                    'pending' => ['label' => 'Menunggu', 'class' => 'secondary'],
                    'review' => ['label' => 'Review', 'class' => 'info'],
                    'interview' => ['label' => 'Interview', 'class' => 'warning'],
                    'accepted' => ['label' => 'Diterima', 'class' => 'success'],
                    'rejected' => ['label' => 'Ditolak', 'class' => 'danger'],
                ];
            @endphp
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

        {{-- Ringkasan Pelamar per Status --}}
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white">
                <h6 class="fw-semibold mb-0">Ringkasan Pelamar per Status</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <ul class="nav nav-tabs border-0 mb-0" id="statusTab" role="tablist">
                        @foreach ($statusConfig as $key => $cfg)
                            @php $count = $groupedByStatus->get($key, collect())->count(); @endphp
                            <li class="nav-item">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#tab-{{ $key }}" type="button">
                                    {{ $cfg['label'] }}
                                    <span class="badge badge-{{ $cfg['class'] }} ms-1"
                                        id="count-badge-{{ $key }}">{{ $count }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#exportModal">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </div>
                </div>

                <div class="tab-content pt-3 border-top">
                    @foreach ($statusConfig as $key => $cfg)
                        @php
                            $list = $groupedByStatus->get($key, collect());
                            $canBulk = count($allowedTransitions[$key] ?? []) > 0; // pending, review, interview
                        @endphp
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $key }}"
                            data-status="{{ $key }}">

                            <p class="text-muted mb-0 empty-placeholder" id="empty-{{ $key }}"
                                style="{{ $list->isEmpty() ? '' : 'display:none' }}">
                                Belum ada pelamar dengan status {{ strtolower($cfg['label']) }}.
                            </p>

                            @if ($canBulk)
                                <div class="d-flex justify-content-between align-items-center mb-2 bulk-toolbar {{ $list->isEmpty() ? 'd-none' : '' }}"
                                    id="bulk-toolbar-{{ $key }}">
                                    <div class="form-check">
                                        <input class="form-check-input chk-all-tab" type="checkbox"
                                            id="chk-all-{{ $key }}" data-tab="{{ $key }}">
                                        <label class="form-check-label small text-muted" for="chk-all-{{ $key }}">
                                            Pilih semua
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary btn-bulk-status d-none"
                                        data-tab="{{ $key }}" data-bs-toggle="modal"
                                        data-bs-target="#bulkStatusModal">
                                        <i class="fas fa-pen"></i> Ubah Status (<span
                                            class="bulk-count-{{ $key }}">0</span>)
                                    </button>
                                </div>
                            @endif

                            <div class="list-group" id="list-group-{{ $key }}">
                                @foreach ($list as $app)
                                    @include('admin.seleksi.ringkasan-item', [
                                        'app' => $app,
                                        'job' => $job,
                                        'profile' => $app->applicantProfile,
                                        'canBulk' => $canBulk,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        @include('admin.seleksi.form')
        @include('admin.seleksi.detail')
        @include('admin.seleksi.info')
        @include('admin.seleksi.export')
        @include('admin.seleksi.bulk')
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let dtPelamar;

        // Dipakai untuk modal ubah status SATU item (termasuk status saat ini sendiri)
        const allowedTransitions = {
            pending: ['pending', 'review', 'interview'],
            review: ['review', 'interview', 'rejected'],
            interview: ['interview', 'accepted', 'rejected'],
            accepted: ['accepted'],
            rejected: ['rejected'],
        };

        // Dipakai untuk bulk update (hanya tujuan yang valid, tanpa status asal)
        const allowedTargetsOnly = {
            pending: ['review', 'interview'],
            review: ['interview', 'rejected'],
            interview: ['accepted', 'rejected'],
            accepted: [],
            rejected: [],
        };

        const statusLabelMap = {
            pending: 'Menunggu',
            review: 'Review',
            interview: 'Interview',
            accepted: 'Diterima',
            rejected: 'Ditolak',
        };

        const currentStatusMap = {};
        let activeBulkTab = null;

        /**
         * Pindahkan/refresh item ringkasan memakai HTML yang sudah dirender ulang
         * dari server (itemHtml) — supaya checkbox, data-tab, dan tombol aksi
         * (termasuk WA) selalu sesuai status terbaru tanpa perlu reload.
         */
        function pindahkanItemRingkasan(appId, oldStatus, newStatus, itemHtml) {
            const $oldItem = $('#ringkasan-item-' + appId);

            if (oldStatus === newStatus) {
                if ($oldItem.length) $oldItem.replaceWith(itemHtml);
                return;
            }

            if ($oldItem.length) $oldItem.remove();

            $('#list-group-' + newStatus).prepend(itemHtml);

            // Hitung ulang badge & empty-state berdasarkan jumlah item riil di DOM
            // (lebih aman daripada increment/decrement manual yang gampang meleset)
            const oldCount = $('#list-group-' + oldStatus).children().length;
            const newCount = $('#list-group-' + newStatus).children().length;

            $('#count-badge-' + oldStatus).text(oldCount);
            $('#count-badge-' + newStatus).text(newCount);

            $('#empty-' + oldStatus).toggle(oldCount === 0);
            $('#empty-' + newStatus).hide();

            refreshBulkToolbar(oldStatus);
            refreshBulkToolbar(newStatus);
        }

        function refreshBulkToolbar(tab) {
            const $items = $('#list-group-' + tab + ' .chk-ringkasan');
            const checkedCount = $items.filter(':checked').length;
            const totalCount = $items.length;

            $('.bulk-count-' + tab).text(checkedCount);
            $('#bulk-toolbar-' + tab).toggleClass('d-none', totalCount === 0);
            $('#bulk-toolbar-' + tab + ' .btn-bulk-status').toggleClass('d-none', checkedCount === 0);
            $('#chk-all-' + tab).prop('checked', checkedCount > 0 && checkedCount === totalCount);
        }

        $(function() {
            $('.select-status').each(function() {
                const $s = $(this);
                currentStatusMap[$s.data('app-id')] = $s.data('current');
            });

            dtPelamar = $('#tabel-pelamar').DataTable({
                stateSave: true,
                stateDuration: -1,
                lengthMenu: [
                    [5, 10, 25, -1],
                    [5, 10, 25, "Semua"]
                ],
                pageLength: 5,
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

            Object.keys(allowedTargetsOnly).forEach(tab => refreshBulkToolbar(tab));
        });

        // ================== MODAL UBAH STATUS (SATU ITEM) ==================
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
                    $('#status-cell-' + appId).html(res.badge_html);
                    $('#aksi-cell-' + appId).html(res.aksi_html);

                    const $row = $('#status-cell-' + appId).closest('tr');
                    dtPelamar.row($row).invalidate().draw(false);

                    pindahkanItemRingkasan(appId, currentStatusMap[appId], res.new_status, res
                        .ringkasan_html);

                    currentStatusMap[appId] = res.new_status;

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

        // ================== EXPORT ==================
        $(document).on('submit', '#form-export', function(e) {
            const dari = $(this).find('[name=tanggal_dari]').val();
            const sampai = $(this).find('[name=tanggal_sampai]').val();

            if (dari && sampai && sampai < dari) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal tidak valid',
                    text: 'Tanggal "Sampai" tidak boleh sebelum tanggal "Dari".',
                    confirmButtonColor: '#8e1a25'
                });
            }
        });

        // ================== BULK UPDATE STATUS ==================
        $(document).on('change', '.chk-all-tab', function() {
            const tab = $(this).data('tab');
            $('#list-group-' + tab + ' .chk-ringkasan').prop('checked', this.checked);
            refreshBulkToolbar(tab);
        });

        $(document).on('change', '.chk-ringkasan', function() {
            refreshBulkToolbar($(this).data('tab'));
        });

        $(document).on('shown.bs.tab', '[data-bs-toggle="tab"]', function() {
            $('.chk-ringkasan, .chk-all-tab').prop('checked', false);
            Object.keys(allowedTargetsOnly).forEach(tab => refreshBulkToolbar(tab));
        });

        $(document).on('click', '.btn-bulk-status', function() {
            activeBulkTab = $(this).data('tab');

            const ids = $('#list-group-' + activeBulkTab + ' .chk-ringkasan:checked')
                .map(function() {
                    return $(this).val();
                }).get();

            const targets = allowedTargetsOnly[activeBulkTab] || [];

            $('#bulk-modal-count').text(ids.length);
            $('#bulk-modal-from').text(statusLabelMap[activeBulkTab]);

            const $select = $('#bulk-status-select').empty();
            targets.forEach(t => {
                $select.append(`<option value="${t}">${statusLabelMap[t]}</option>`);
            });
        });

        $(document).on('submit', '#form-bulk-status', function(e) {
            e.preventDefault();

            if (!activeBulkTab) return;

            const ids = $('#list-group-' + activeBulkTab + ' .chk-ringkasan:checked')
                .map(function() {
                    return $(this).val();
                }).get();
            const status = $('#bulk-status-select').val();

            if (ids.length === 0 || !status) return;

            const $submitBtn = $(this).find('button[type=submit]');
            $submitBtn.prop('disabled', true).text('Memproses...');

            $.ajax({
                url: "{{ route('admin.seleksi.bulk-update-status') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids,
                    status: status,
                },
                success: function(res) {
                    // Loop SEKALI SAJA — tidak ada nested forEach lagi
                    res.updated.forEach(function(item) {
                        $('#status-cell-' + item.id).html(item.badge_html);
                        $('#aksi-cell-' + item.id).html(item.aksi_html);

                        pindahkanItemRingkasan(item.id, item.old_status, item.new_status, item
                            .ringkasan_html);

                        currentStatusMap[item.id] = item.new_status;

                        const $row = $('#status-cell-' + item.id).closest('tr');
                        if ($row.length) dtPelamar.row($row).invalidate();
                    });

                    dtPelamar.draw(false);

                    $('.chk-ringkasan, .chk-all-tab').prop('checked', false);
                    $('#bulkStatusModal').modal('hide');

                    let detail = '';
                    if (res.skipped.length) {
                        detail = '\n\nDilewati: ' + res.skipped.map(s => s.nama + ' (' + s.alasan +
                            ')').join(', ');
                    }

                    Swal.fire({
                        icon: res.skipped.length ? 'warning' : 'success',
                        title: 'Selesai',
                        text: res.message + detail,
                        confirmButtonColor: '#8e1a25',
                    });
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan, silakan coba lagi.';
                    if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg,
                        confirmButtonColor: '#8e1a25'
                    });
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text('Terapkan');
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
