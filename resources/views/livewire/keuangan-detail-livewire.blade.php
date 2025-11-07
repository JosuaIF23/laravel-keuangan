<div class="mt-3">
    <div class="card shadow-sm">
        {{-- 🔹 Header --}}
        <div class="card-header d-flex">
            <div class="flex-fill">
                <a href="{{ route('app.home') }}" class="text-decoration-none">
                    <small class="text-muted">&lt; Kembali</small>
                </a>

                <h3 class="mt-1">
                    {{ $keuangan->judul }}
                    @if ($keuangan->tipe === 'pemasukan')
                        <small class="badge bg-success">Pemasukan</small>
                    @else
                        <small class="badge bg-danger">Pengeluaran</small>
                    @endif
                </h3>
            </div>

            <div>
                <button class="btn btn-warning" data-bs-target="#editBuktiKeuanganModal" data-bs-toggle="modal">
                    <i class="bi bi-upload"></i> Ubah Bukti
                </button>
            </div>
        </div>

        {{-- 🔹 Body --}}
        <div class="card-body">

            {{-- 📎 Jika bukti transaksi ada --}}
            @if ($keuangan->bukti)
                <div class="text-center mb-3">
                    @if (Str::endsWith($keuangan->bukti, '.pdf'))
                        {{-- Jika file PDF --}}
                        <a href="{{ asset('storage/' . $keuangan->bukti) }}" target="_blank"
                            class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-pdf"></i> Lihat Bukti (PDF)
                        </a>
                    @else
                        {{-- Jika file gambar --}}
                        <img src="{{ asset('storage/' . $keuangan->bukti) }}" alt="Bukti Transaksi"
                            class="img-fluid rounded shadow-sm mb-2" style="max-height: 250px;">
                    @endif
                </div>
                <hr>
            @else
                {{-- 🚫 Jika belum ada bukti --}}
                <div class="alert alert-light text-center border">
                    <p class="text-muted m-0">Belum ada bukti transaksi yang diunggah.</p>
                </div>
            @endif

            {{-- 📋 Detail transaksi --}}
            <table class="table table-borderless mb-4">
                <tbody>
                    <tr>
                        <th style="width: 160px;">Judul Transaksi</th>
                        <td>{{ $keuangan->judul }}</td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td>
                            @if ($keuangan->tipe === 'pemasukan')
                                <span class="badge bg-success">Pemasukan</span>
                            @else
                                <span class="badge bg-danger">Pengeluaran</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td><strong>Rp {{ number_format($keuangan->jumlah, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ \Carbon\Carbon::parse($keuangan->tanggal)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $keuangan->keterangan ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 🧩 Modal untuk ubah bukti --}}
    @include('components.modals.keuangan.edit-cover')
</div>
