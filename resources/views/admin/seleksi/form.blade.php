        @foreach ($applications as $app)
            @php
                $statusConfig = [
                    'pending' => ['label' => 'Menunggu', 'class' => 'secondary'],
                    'review' => ['label' => 'Review', 'class' => 'info'],
                    'interview' => ['label' => 'Interview', 'class' => 'warning'],
                    'accepted' => ['label' => 'Diterima', 'class' => 'success'],
                    'rejected' => ['label' => 'Ditolak', 'class' => 'danger'],
                ];
                $profile = $app->applicantProfile;
            @endphp
            <div class="modal fade" id="statusModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.seleksi.update-status', $app->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Ubah Status - {{ $profile->nama }}</h6>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small">Status</label>
                                    <select name="status" class="form-control" required>
                                        @foreach ($statusConfig as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ $app->status == $key ? 'selected' : '' }}>
                                                {{ $val['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small">Catatan (opsional)</label>
                                    <textarea name="catatan_hrd" class="form-control" rows="3">{{ $app->catatan_hrd }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
