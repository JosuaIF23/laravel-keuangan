<form wire:submit.prevent="editBuktiKeuangan">
    <div class="modal fade" tabindex="-1" id="editBuktiKeuanganModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if ($keuangan && $keuangan->bukti)
                        <div class="mb-3 text-center">
                            <p class="text-muted small mb-1">Bukti lama:</p>
                            <img src="{{ asset('storage/' . $keuangan->bukti) }}" alt="Bukti lama" class="img-thumbnail"
                                style="max-height: 150px;">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Unggah Bukti Baru</label>
                        <input type="file" class="form-control" wire:model="editBuktiFile"
                            accept="image/*,application/pdf">
                        @error('editBuktiFile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div wire:loading wire:target="editBuktiFile" class="text-muted mt-1">
                            <small>⏳ Mengunggah...</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @if (!$editBuktiFile) disabled @endif>
                        Simpan Bukti
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
