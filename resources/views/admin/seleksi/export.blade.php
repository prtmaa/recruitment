<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="GET" action="{{ route('admin.seleksi.export', $job->id) }}" id="form-export">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold mb-0">Export Data Pelamar</h6>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <div class="row">
                            @foreach ($statusConfig as $key => $cfg)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]"
                                            value="{{ $key }}" id="export-status-{{ $key }}">
                                        <label class="form-check-label" for="export-status-{{ $key }}">
                                            {{ $cfg['label'] }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Kosongkan semua untuk export semua status.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Tanggal Melamar Dari</label>
                            <input type="date" name="tanggal_dari" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Sampai</label>
                            <input type="date" name="tanggal_sampai" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
