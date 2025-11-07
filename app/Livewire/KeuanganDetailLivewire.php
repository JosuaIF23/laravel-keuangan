<?php

namespace App\Livewire;

use App\Models\Keuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class KeuanganDetailLivewire extends Component
{
    use WithFileUploads;

    public $keuangan;
    public $auth;
    public $editBuktiFile; // ✅ konsisten dengan yang dipakai di Blade

    public function mount()
    {
        $this->auth = Auth::user();

        $id = request()->route('id');
        $target = Keuangan::where('id', $id)->first();

        if (!$target) {
            return redirect()->route('app.home');
        }

        $this->keuangan = $target;

        // 🔧 Pastikan symbolic link ada
        $this->ensureStorageLinkExists();
    }

    /**
     * 🔗 Pastikan public/storage → storage/app/public tersambung
     */
    private function ensureStorageLinkExists()
    {
        $publicPath = public_path('storage');
        $targetPath = storage_path('app/public');

        // Jika belum ada symbolic link
        if (!File::exists($publicPath)) {
            try {
                Artisan::call('storage:link');
                logger('✅ Symbolic link public/storage berhasil dibuat otomatis.');
            } catch (\Exception $e) {
                logger('⚠️ Gagal membuat symbolic link otomatis: ' . $e->getMessage());
            }
        } elseif (is_dir($publicPath) && !is_link($publicPath)) {
            // Kalau public/storage berupa folder biasa, hapus dan buat ulang
            File::deleteDirectory($publicPath);
            Artisan::call('storage:link');
        }
    }

    public function render()
    {
        return view('livewire.keuangan-detail-livewire');
    }

    // ========================================================
    // 📎 Upload / Update Bukti Transaksi
    // ========================================================
    public function editBuktiKeuangan()
    {
        $this->validate([
            'editBuktiFile' => 'required|file|max:2048|mimes:jpg,jpeg,png,pdf',
        ], [
            'editBuktiFile.required' => 'Silakan pilih file bukti.',
            'editBuktiFile.mimes' => 'Format file harus JPG, PNG, atau PDF.',
            'editBuktiFile.max' => 'Ukuran file maksimal 2MB.',
        ]);

        if ($this->editBuktiFile) {
            // 🧹 Hapus file lama jika ada
            if ($this->keuangan->bukti && Storage::disk('public')->exists($this->keuangan->bukti)) {
                Storage::disk('public')->delete($this->keuangan->bukti);
            }

            // 💾 Simpan file baru ke storage/app/public/bukti
            $userId = $this->auth->id;
            $timestamp = now()->format('YmdHis');
            $extension = $this->editBuktiFile->getClientOriginalExtension();
            $filename = "{$userId}_{$timestamp}.{$extension}";

            // Simpan file di disk 'public' agar bisa diakses lewat /storage/
            $path = $this->editBuktiFile->storeAs('bukti', $filename, 'public');

            // 🗃️ Simpan path relatif (contoh: bukti/1_20251104123000.png)
            $this->keuangan->bukti = $path;
            $this->keuangan->save();

            $this->dispatch('keuanganUpdated');
        }

        // 🔄 Reset input
        $this->reset(['editBuktiFile']);

        // ✅ Tutup modal & kirim notifikasi Livewire
        $this->dispatch('closeModal', id: 'editBuktiKeuanganModal');
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Bukti transaksi berhasil diperbarui.'
        ]);
    }
}
