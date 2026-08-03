<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal-form" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

        <form action="" method="post" enctype="multipart/form-data" data-toggle="validator" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group row">
                        <label for="judul" class="col-md-2 col-md-offset-1 control-label">Posisi</label>
                        <div class="col-md-10">
                            <input type="text" name="judul" id="judul" class="form-control" required
                                oninvalid="this.setCustomValidity('Posisi harus diisi')"
                                oninput="this.setCustomValidity('')" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="deskripsi" class="col-md-2 col-md-offset-1 control-label">Deskripsi</label>
                        <div class="col-md-10">
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote" required></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="persyaratan" class="col-md-2 col-md-offset-1 control-label">Persyaratan</label>
                        <div class="col-md-10">
                            <textarea name="persyaratan" id="persyaratan" class="form-control summernote" required></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label for="tanggal_tutup" class="col-md-3 col-md-offset-1 control-label">Tanggal
                                    Berakhir</label>
                                <div class="col-md-8">
                                    <input type="date" name="tanggal_tutup" id="tanggal_tutup" class="form-control"
                                        required oninvalid="this.setCustomValidity('Tanggal berakhir harus diisi')"
                                        oninput="this.setCustomValidity('')">
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-3 control-label">Publish</label>

                                <div class="col-md-4">
                                    <input type="hidden" name="is_active" value="0">

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                            name="is_active" value="1">

                                        <label class="custom-control-label" for="is_active">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i
                            class="fa fa-xmark"></i> Batal</button>
                    <button class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>

    </div>
</div>
