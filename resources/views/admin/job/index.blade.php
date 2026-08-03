@extends('admin.layouts.master')

@section('tittle')
    Data Loker
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"> <a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Loker</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="btn-group">
                                    <button onclick="addForm('{{ route('admin.job.store') }}')"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus-circle"></i> Tambah Data
                                    </button>
                                </div>
                            </div>


                            <div class="card-body table-responsive">
                                <form action="" class="form-produk" method="post">
                                    @csrf
                                    <table class="table text-center table-bordered">
                                        <thead>
                                            <th style="width: 20px;">No</th>
                                            <th>Kode</th>
                                            <th>Posisi</th>
                                            <th>Deskripsi</th>
                                            <th>Persyaratan</th>
                                            <th>Tanggal Berakhir</th>
                                            <th>Status</th>
                                            <th style="width: 220px;">Aksi</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </form>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
        </div>

        @include('admin.job.form')
        @include('admin.job.interview')
    @endsection

    @push('js')
        <script>
            let table;
            $(function() {
                table = $('.table').DataTable({
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
                        url: '{{ route('admin.job.data') }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false
                        },
                        {
                            data: 'kode'
                        },
                        {
                            data: 'judul'
                        },
                        {
                            data: 'deskripsi'
                        },
                        {
                            data: 'persyaratan'
                        },
                        {
                            data: 'tanggal'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'aksi',
                            "searchable": false,
                            "orderable": false
                        },
                    ],
                    createdRow: function(row, data, dataIndex) {
                        $('td:eq(3)', row).addClass('text-left');
                        $('td:eq(4)', row).addClass('text-left');
                    }
                });

                $('#modal-form').validator().on('submit', function(e) {
                    if (!e.preventDefault()) {
                        $.ajax({
                                enctype: 'multipart/form-data',
                                url: $('#modal-form form').attr('action'),
                                type: $('#modal-form form').attr('method'),
                                data: new FormData($('#modal-form form')[0]),
                                async: false,
                                processData: false,
                                contentType: false
                            })
                            .done((response) => {
                                $('#modal-form').modal('hide');
                                table.ajax.reload();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data berhasil disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            })
                            .fail((errors) => {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: 'Data gagal disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            });
                    }
                })

            });

            function addForm(url) {
                $('#modal-form').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
                $('#modal-form .modal-title').text('Tambah Data');

                $('#modal-form form')[0].reset();
                $('#deskripsi').summernote('code', '');
                $('#persyaratan').summernote('code', '');
                $('#modal-form form').attr('action', url);
                $('#modal-form [name=_method]').val('post');
                $('#modal-form [name=judul]').focus();
                $('#is_active').prop('checked', true);
            }

            function editForm(url) {
                $('#modal-form').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
                $('#modal-form .modal-title').text('Edit Data');
                $('#modal-form form')[0].reset();
                $('#deskripsi').summernote('code', '');
                $('#persyaratan').summernote('code', '');
                $('#modal-form form').attr('action', url);
                $('#modal-form [name=_method]').val('put');
                $('#modal-form [name=judul]').focus();

                $.get(url)
                    .done((response) => {
                        $('#modal-form [name=judul]').val(response.judul);
                        $('[name=deskripsi]').summernote('code', response.deskripsi);
                        $('[name=persyaratan]').summernote('code', response.persyaratan);
                        $('#modal-form [name=tanggal_tutup]').val(response.tanggal_tutup);
                        $('#is_active').prop('checked', response.is_active == 1);
                    })
                    .fail((errors) => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Data gagal ditampilkan',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    });
            }

            function deleteData(url) {
                Swal.fire({
                    title: 'Yakin?',
                    text: "Data akan dihapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post(url, {
                                '_token': $('[name=csrf-token]').attr('content'),
                                '_method': 'delete'
                            })
                            .done((response) => {
                                table.ajax.reload();
                                $('.alertdelete').fadeIn();

                                setTimeout(() => {
                                    $('.alertdelete').fadeOut();
                                }, 3000);
                            })
                            .fail((errors) => {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: 'Data gagal dihapus',
                                })
                            });
                    }
                })
            }
        </script>
        <script>
            function inputInterview(getUrl, saveUrl, jobTitle) {
                $('#modal-interview').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');

                $('#interview-job-title').text(jobTitle);
                $('#form-interview')[0].reset();
                $('#form-interview').attr('action', saveUrl);

                // ambil data existing (kalau sudah pernah diisi sebelumnya)
                $.get(getUrl)
                    .done((response) => {
                        if (response) {
                            $('#interview-tanggal').val(response.tanggal_interview?.substring(0, 10));
                            $('#interview-jam-mulai').val(response.jam_mulai?.substring(0, 5));
                            $('#interview-jam-selesai').val(response.jam_selesai?.substring(0, 5));
                            $('#interview-tempat').val(response.tempat);
                            $('#interview-catatan').val(response.catatan);
                        }
                    });
            }

            $('#form-interview').on('submit', function(e) {
                e.preventDefault();

                $.post($(this).attr('action'), $(this).serialize())
                    .done((response) => {
                        $('#modal-interview').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .fail((errors) => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Jadwal gagal disimpan',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
            });
        </script>
        <script>
            $(function() {
                // Konfigurasi toolbar disatukan biar konsisten & gampang diubah
                const summernoteConfig = {
                    height: 150,
                    minHeight: 150,
                    maxHeight: 300,
                    dialogsInBody: true, // biar dialog link/gambar tidak ketutup modal
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                };

                // Init Summernote HANYA setelah modal benar-benar tampil
                $('#modal-form').on('shown.bs.modal', function() {
                    $('#deskripsi').summernote({
                        ...summernoteConfig,
                        placeholder: 'Masukkan deskripsi...'
                    });

                    $('#persyaratan').summernote({
                        ...summernoteConfig,
                        placeholder: 'Masukkan persyaratan...'
                    });
                });

                // Hancurkan instance saat modal ditutup, biar tidak dobel saat dibuka lagi
                $('#modal-form').on('hidden.bs.modal', function() {
                    $('#deskripsi').summernote('destroy');
                    $('#persyaratan').summernote('destroy');
                });
            });
        </script>
    @endpush
