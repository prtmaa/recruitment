@foreach ($applications as $app)
    @php $profile = $app->applicantProfile; @endphp
    <div class="modal fade" id="detailModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold">Detail Pelamar</h6>
                </div>
                <div class="modal-body">

                    {{-- Header profil --}}
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <img src="{{ $profile->foto ? asset('storage/' . $profile->foto) : asset('images/default-avatar.png') }}"
                            class="rounded-circle mr-3" width="70" height="70" style="object-fit:cover">
                        <div>
                            <h5 class="mb-1 fw-semibold">{{ $profile->nama }}</h5>
                            <small class="text-muted d-block">{{ $profile->user->email }}</small>
                            <small class="text-muted">
                                <i class="fas fa-phone me-1 mr-1"></i>{{ $profile->no_hp ?? '-' }}
                            </small>
                        </div>
                    </div>

                    {{-- Data Diri --}}
                    <h6 class="fw-semibold text-primary mb-3">
                        <i class="fas fa-id-card me-1"></i> Data Diri
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">NIK</small>
                            <span class="fw-medium">{{ $profile->nik ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Jenis Kelamin</small>
                            <span class="fw-medium">{{ $profile->kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                            <span class="fw-medium">
                                {{ $profile->tempat_lahir ?? '-' }},
                                {{ $profile->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Agama</small>
                            <span class="fw-medium">{{ $profile->agama ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Status Pernikahan</small>
                            <span class="fw-medium">{{ $profile->status ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">BPJS</small>
                            <span class="fw-medium">{{ $profile->bpjs ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">NPWP</small>
                            <span class="fw-medium">{{ $profile->npwp ?? '-' }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <small class="text-muted d-block">Alamat KTP</small>
                            <span class="fw-medium">{{ $profile->alamat ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Domisili</small>
                            <span class="fw-medium">{{ $profile->domisili ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Pendidikan --}}
                    <h6 class="fw-semibold text-primary mb-3">
                        <i class="fas fa-graduation-cap me-1"></i> Pendidikan
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Jenjang</small>
                            <span class="fw-medium">{{ $profile->pendidikan ?? '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Jurusan</small>
                            <span class="fw-medium">{{ $profile->jurusan ?? '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Asal Sekolah/Kampus</small>
                            <span class="fw-medium">{{ $profile->sekolah ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Pengalaman Kerja --}}
                    <h6 class="fw-semibold text-primary mb-3">
                        <i class="fas fa-briefcase me-1"></i> Pengalaman Kerja
                    </h6>
                    @forelse ($profile->workExperiences as $exp)
                        <div class="border rounded-3 p-3 mb-2">
                            <div class="d-flex justify-content-between flex-wrap gap-1">
                                <span class="fw-semibold">{{ $exp->posisi }} — {{ $exp->perusahaan }}</span>
                                @if ($exp->masih_bekerja == '1')
                                    <span class="badge text-bg-success">Masih Bekerja</span>
                                @endif
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-map-marker-alt me-1 mr-1"></i>{{ $exp->kota }} &middot;
                                {{ \Carbon\Carbon::parse($exp->mulai_kerja)->translatedFormat('M Y') }}
                                -
                                {{ $exp->masih_bekerja == '1' ? 'Sekarang' : ($exp->berhenti_kerja ? \Carbon\Carbon::parse($exp->berhenti_kerja)->translatedFormat('M Y') : '-') }}
                            </small>
                            @if ($exp->tanggung_jawab)
                                <p class="mb-0 small">{{ $exp->tanggung_jawab }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted small">Belum ada pengalaman kerja yang dicantumkan.</p>
                    @endforelse

                    {{-- CV --}}
                    <h6 class="fw-semibold text-primary mb-3 mt-4">
                        <i class="fas fa-file-alt me-1"></i> Dokumen
                    </h6>
                    @if ($profile->cv)
                        <a href="{{ asset('storage/' . $profile->cv) }}" target="_blank"
                            class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> Lihat
                        </a>
                    @else
                        <p class="text-muted small mb-0">Dokumen belum diunggah.</p>
                    @endif

                    {{-- Riwayat Lamaran --}}
                    <h6 class="fw-semibold text-primary mb-3 mt-4">
                        <i class="fas fa-history me-1"></i> Riwayat Lamaran
                    </h6>

                    @php
                        $statusConfig = [
                            'pending' => [
                                'label' => 'Menunggu',
                                'class' => 'secondary',
                                'icon' => 'fa-hourglass-half',
                            ],
                            'review' => [
                                'label' => 'Review',
                                'class' => 'info',
                                'icon' => 'fa-eye',
                            ],
                            'interview' => [
                                'label' => 'Interview',
                                'class' => 'warning',
                                'icon' => 'fa-comments',
                            ],
                            'accepted' => [
                                'label' => 'Diterima',
                                'class' => 'success',
                                'icon' => 'fa-check',
                            ],
                            'rejected' => [
                                'label' => 'Ditolak',
                                'class' => 'danger',
                                'icon' => 'fa-xmark',
                            ],
                        ];
                    @endphp

                    @if ($profile->applications->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Posisi</th>
                                        <th>Tanggal Melamar</th>
                                        <th>Progres</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profile->applications as $riwayat)
                                        @php $cfg = $statusConfig[$riwayat->status] ?? $statusConfig['pending']; @endphp
                                        <tr class="{{ $riwayat->id === $app->id ? 'table-primary' : '' }}">
                                            <td>
                                                {{ $riwayat->job->judul ?? '-' }}
                                                @if ($riwayat->id === $app->id)
                                                    <span class="badge text-bg-primary ms-1"
                                                        style="font-size: 0.65rem;">Lamaran ini</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $riwayat->tanggal_melamar->translatedFormat('d M Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge text-bg-{{ $cfg['class'] }}">
                                                    <i
                                                        class="fas {{ $cfg['icon'] }} me-1  mr-1"></i>{{ $cfg['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small mb-0">Belum ada riwayat lamaran.</p>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#statusModal{{ $app->id }}" data-bs-dismiss="modal">
                        <i class="fas fa-pen me-1"></i> Ubah Status
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
