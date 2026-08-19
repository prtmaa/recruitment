@php
    $jadwal = $job->interviewSchedule ?? null;
@endphp

<div class="d-flex justify-content-center gap-1">
    <div class="btn-group">
        <button class="btn btn-sm btn-warning" title="Lihat Detail" data-bs-toggle="modal"
            data-bs-target="#detailModal{{ $app->id }}">
            <i class="fas fa-eye"></i>
        </button>

        <button class="btn btn-sm btn-info" title="Ubah Status" data-bs-toggle="modal"
            data-bs-target="#statusModal{{ $app->id }}">
            <i class="fas fa-pen"></i>
        </button>

        @if ($app->status === 'interview' && $profile->no_hp_wa && $jadwal)
            @php
                $pesanWa =
                    "Halo {$profile->nama},\n\n" .
                    "Selamat! Anda dipanggil untuk mengikuti *interview* pada tahap seleksi posisi *{$job->judul}* di PT Widodo Makmur Unggas.\n\n" .
                    'Tanggal: ' .
                    formatTanggalIndo($jadwal->tanggal_interview) .
                    "\n" .
                    'Waktu: ' .
                    \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') .
                    ($jadwal->jam_selesai ? ' - ' . \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '') .
                    " WIB\n" .
                    "Tempat: {$jadwal->tempat}\n" .
                    ($jadwal->catatan ? "\nCatatan: {$jadwal->catatan}\n" : '') .
                    "\nMohon konfirmasi kehadiran Anda melalui pesan ini.\n\nTerima kasih.";
            @endphp
            <a href="https://wa.me/{{ $profile->no_hp_wa }}?text={{ urlencode($pesanWa) }}" target="_blank"
                class="btn btn-sm btn-success" title="Kirim Panggilan Interview via WA">
                <i class="fab fa-whatsapp"></i>
            </a>
        @elseif ($app->status === 'interview' && !$jadwal)
            <button class="btn btn-sm btn-outline-secondary" title="Isi jadwal interview di halaman Data Loker dulu"
                disabled>
                <i class="fab fa-whatsapp"></i>
            </button>
        @endif
    </div>
</div>
