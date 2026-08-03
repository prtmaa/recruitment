<div class="modal fade" id="modal-interview" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-interview">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        Jadwal Interview - <span id="interview-job-title"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Interview</label>
                        <input type="date" name="tanggal_interview" id="interview-tanggal" class="form-control"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="interview-jam-mulai" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="interview-jam-selesai"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tempat</label>
                        <input type="text" name="tempat" id="interview-tempat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan Tambahan</label>
                        <textarea name="catatan" id="interview-catatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
