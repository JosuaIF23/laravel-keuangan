<form wire:submit.prevent="deleteKeuangan">
    <div class="modal fade" tabindex="-1" id="deleteKeuanganModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        Apakah kamu yakin ingin menghapus transaksi
                        <strong>"{{ $deleteJudul }}"</strong>?
                    </div>
                    <label>Konfirmasi Judul</label>
                    <input type="text" class="form-control" wire:model="deleteConfirm"
                        placeholder="Ketik '{{ $deleteJudul }}'">
                    @error('deleteConfirm')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>
