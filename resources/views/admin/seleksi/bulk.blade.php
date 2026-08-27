<div class="modal fade" id="bulkStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-bulk-status">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold mb-0">Ubah Status Massal</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Mengubah status untuk <strong id="bulk-modal-count">0</strong> pelamar terpilih
                        dari status <strong id="bulk-modal-from"></strong>.
                    </p>

                    <div class="mb-0">
                        <label class="form-label small">Ubah ke Status</label>
                        <select name="status" id="bulk-status-select" class="form-control" required></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </div>
        </form>
    </div>
</div>
