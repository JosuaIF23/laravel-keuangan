<form wire:submit.prevent="editKeuangan">
    <div class="modal fade" tabindex="-1" id="editKeuanganModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Judul</label>
                        <input type="text" class="form-control" wire:model="editJudul">
                        @error('editJudul') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label>Keterangan</label>
                        <textarea class="form-control" wire:model="editKeterangan"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Tipe</label>
                            <select class="form-select" wire:model="editTipe">
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" wire:model="editJumlah">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" wire:model="editTanggal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Perbarui</button>
                </div>
            </div>
        </div>
    </div>
</form>
