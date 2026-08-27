<div class="list-group-item d-flex justify-content-between align-items-center" id="ringkasan-item-{{ $app->id }}">
    <div class="d-flex align-items-center gap-2">
        @if ($canBulk)
            <div class="d-flex align-items-center ml-2 mr-3">
                <input type="checkbox" class="form-check-input chk-ringkasan" value="{{ $app->id }}"
                    data-tab="{{ $app->status }}">
            </div>
        @endif
        <img src="{{ $profile->foto ? asset('storage/' . $profile->foto) : asset('images/default-avatar.png') }}"
            class="rounded-circle mr-3" width="36" height="36" style="object-fit:cover">
        <div>
            <span class="fw-semibold d-block">{{ $profile->nama }}</span>
            <small class="text-muted">
                {{ $app->tanggal_melamar->translatedFormat('d M Y') }} &middot;
                {{ $profile->pendidikan }}@if ($profile->jurusan)
                    - {{ $profile->jurusan }}
                @endif
            </small>
        </div>
    </div>
    @include('admin.seleksi.aksi', ['app' => $app, 'job' => $job, 'profile' => $profile])
</div>
