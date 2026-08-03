{{-- Modal Warning --}}
<div class="modal fade" id="scamWarningModal" tabindex="-1" aria-labelledby="scamWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content scam-warning-card border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-md-4 pt-0">

                {{-- Warning Banner --}}
                <div class="scam-alert-banner d-flex align-items-center justify-content-center gap-2 mb-3">
                    <i class="fas fa-exclamation-triangle scam-alert-icon"></i>
                    <span class="scam-alert-text">WASPADA PENIPUAN !</span>
                </div>

                {{-- Info Box --}}
                <div class="scam-info-box p-3 p-md-4 mb-3 text-center">
                    <p class="mb-2 fw-bold">
                        {{ $setting->teks1 }}
                    </p>
                </div>

                {{-- Body Text --}}
                <div class="text-center mb-2 scam-body-text">
                    <p class="mb-3 fw-semibold">
                        {{ $setting->teks2 }}
                    </p>
                    <p class="mb-0 fw-bold">
                        {{ $setting->nama }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
