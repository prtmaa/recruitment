<div class="modal fade" id="statusFlowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold mb-0">Alur Status Lamaran</h6>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-4">
                    Status lamaran hanya bisa berpindah sesuai alur di bawah ini.
                </p>

                @php
                    $flow = [
                        ['label' => 'Menunggu', 'to' => ['Review', 'Interview']],
                        ['label' => 'Review', 'to' => ['Interview', 'Ditolak']],
                        ['label' => 'Interview', 'to' => ['Diterima', 'Ditolak']],
                        ['label' => 'Diterima', 'to' => []],
                        ['label' => 'Ditolak', 'to' => []],
                    ];
                @endphp

                <div class="d-flex flex-column">
                    @foreach ($flow as $i => $item)
                        <div class="d-flex align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="fw-semibold" style="width: 110px; flex-shrink: 0;">
                                {{ $item['label'] }}
                            </div>
                            <div class="text-muted">
                                @if (count($item['to']))
                                    <span class="mr-1">&rarr;</span>{{ implode(', ', $item['to']) }}
                                @else
                                    <span class="fst-italic">Status akhir, tidak bisa diubah lagi</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-3">

                <p class="text-muted small mb-0">
                    Contoh: lamaran berstatus <strong>Menunggu</strong> tidak bisa langsung
                    diubah menjadi <strong>Diterima</strong> atau <strong>Ditolak</strong> —
                    harus melewati tahap <strong>Interview</strong> terlebih dahulu.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
