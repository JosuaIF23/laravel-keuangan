<?php

namespace App\Livewire;

use App\Models\Keuangan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HomeKeuanganLivewire extends Component
{
    use WithPagination;

    public $auth;
    public $search = '';
    public $filterTipe = ''; // pemasukan / pengeluaran
    protected $paginationTheme = 'bootstrap';

    // Inisialisasi user login
    public function mount()
    {
        $this->auth = Auth::user();
    }

    // Reset halaman saat filter berubah
    public function updated($property)
    {
        if (in_array($property, ['search', 'filterTipe'])) {
            $this->resetPage();
        }
    }

    protected $listeners = ['keuanganUpdated' => '$refresh'];


    public function render()
    {
        $query = Keuangan::where('user_id', $this->auth->id)
            ->when($this->search, fn($q) => $q->where('judul', 'like', "%{$this->search}%"))
            ->when($this->filterTipe !== '', fn($q) => $q->where('tipe', $this->filterTipe))
            ->orderBy('tanggal', 'desc');

        $keuangans = $query->paginate(6);

        // Hitung total pemasukan dan pengeluaran
        $totalPemasukan = Keuangan::where('user_id', $this->auth->id)
            ->where('tipe', 'pemasukan')
            ->sum('jumlah');

        $totalPengeluaran = Keuangan::where('user_id', $this->auth->id)
            ->where('tipe', 'pengeluaran')
            ->sum('jumlah');

        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('livewire.home-keuangan-livewire', [
            'keuangans' => $keuangans,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
        ]);
    }

    // ============================================================
    // 🔹 Tambah Transaksi
    // ============================================================
    public $addJudul;
    public $addKeterangan;
    public $addTipe = '';
    public $addJumlah;
    public $addTanggal;

    public function addKeuangan()
    {
        $this->validate([
            'addJudul' => 'required|string|max:255',
            'addKeterangan' => 'nullable|string',
            'addTipe' => 'required|in:pemasukan,pengeluaran',
            'addJumlah' => 'required|numeric|min:0',
            'addTanggal' => 'required|date',
        ]);

        Keuangan::create([
            'user_id' => $this->auth->id,
            'judul' => $this->addJudul,
            'keterangan' => $this->addKeterangan,
            'tipe' => $this->addTipe,
            'jumlah' => $this->addJumlah,
            'tanggal' => $this->addTanggal,
        ]);

        $this->reset(['addJudul', 'addKeterangan', 'addTipe', 'addJumlah', 'addTanggal']);
        $this->dispatch('closeModal', id: 'addKeuanganModal');
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Transaksi baru berhasil ditambahkan.'
        ]);
        $this->dispatch('keuanganUpdated');
    }

    // ============================================================
    // 🔹 Edit Transaksi
    // ============================================================
    public $editId;
    public $editJudul;
    public $editKeterangan;
    public $editTipe;
    public $editJumlah;
    public $editTanggal;

    public function prepareEdit($id)
    {
        $data = Keuangan::find($id);
        if (!$data) return;

        $this->editId = $data->id;
        $this->editJudul = $data->judul;
        $this->editKeterangan = $data->keterangan;
        $this->editTipe = $data->tipe;
        $this->editJumlah = $data->jumlah;
        $this->editTanggal = $data->tanggal;

        $this->dispatch('showModal', id: 'editKeuanganModal');
    }

    public function editKeuangan()
    {
        $this->validate([
            'editJudul' => 'required|string|max:255',
            'editKeterangan' => 'nullable|string',
            'editTipe' => 'required|in:pemasukan,pengeluaran',
            'editJumlah' => 'required|numeric|min:0',
            'editTanggal' => 'required|date',
        ]);

        $data = Keuangan::find($this->editId);
        if (!$data) {
            $this->addError('editJudul', 'Data transaksi tidak ditemukan.');
            return;
        }

        $data->update([
            'judul' => $this->editJudul,
            'keterangan' => $this->editKeterangan,
            'tipe' => $this->editTipe,
            'jumlah' => $this->editJumlah,
            'tanggal' => $this->editTanggal,
        ]);

        $this->reset(['editId', 'editJudul', 'editKeterangan', 'editTipe', 'editJumlah', 'editTanggal']);
        $this->dispatch('closeModal', id: 'editKeuanganModal');
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Transaksi berhasil diperbarui.'
        ]);
        $this->dispatch('keuanganUpdated');
    }

    // ============================================================
    // 🔹 Hapus Transaksi
    // ============================================================
    public $deleteId;
    public $deleteJudul;
    public $deleteConfirm;

    public function prepareDelete($id)
    {
        $data = Keuangan::find($id);
        if (!$data) return;

        $this->deleteId = $data->id;
        $this->deleteJudul = $data->judul;

        $this->dispatch('showModal', id: 'deleteKeuanganModal');
    }

    public function deleteKeuangan()
    {
        if ($this->deleteConfirm !== $this->deleteJudul) {
            $this->addError('deleteConfirm', 'Konfirmasi tidak sesuai.');
            return;
        }

        Keuangan::destroy($this->deleteId);

        $this->reset(['deleteId', 'deleteJudul', 'deleteConfirm']);
        $this->dispatch('closeModal', id: 'deleteKeuanganModal');
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Dihapus!',
            'message' => 'Transaksi berhasil dihapus.'
        ]);
        $this->dispatch('keuanganUpdated');
    }
}
