<div class="mt-3">
    {{-- 📊 Statistik Keuangan --}}
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="mb-3">Statistik Keuangan Kamu</h5>

            {{-- Loader Statistik --}}
            <div wire:loading wire:target="search,filterTipe" class="text-muted mb-2">
                <small>🔄 Memperbarui statistik...</small>
            </div>

            <div id="keuanganChart" style="height: 250px;"></div>

            {{-- 🔢 Hidden data untuk chart --}}
            <input type="hidden" id="chart-pemasukan" value="{{ $totalPemasukan }}">
            <input type="hidden" id="chart-pengeluaran" value="{{ $totalPengeluaran }}">
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="m-0">Hai, {{ $auth->name }}</h3>
            <a href="{{ route('auth.logout') }}" class="btn btn-warning">Keluar</a>
        </div>

        <div class="card-body">
            {{-- 🔍 Filter & Pencarian --}}
            <div class="d-flex mb-3">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control me-2"
                    placeholder="Cari transaksi..." style="max-width: 300px;">
                <select wire:model.live="filterTipe" class="form-select" style="max-width: 200px;">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            {{-- Loader saat memuat --}}
            <div wire:loading wire:target="search,filterTipe" class="text-muted mb-2">
                <small>🔄 Memuat data...</small>
            </div>

            {{-- Daftar Transaksi --}}
            <div class="d-flex mb-2">
                <div class="flex-fill">
                    <h3>Daftar Transaksi</h3>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKeuanganModal">
                        Tambah Transaksi
                    </button>
                </div>
            </div>

            <table class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($keuangans as $key => $k)
                        <tr>
                            <td>{{ ($keuangans->currentPage() - 1) * $keuangans->perPage() + $key + 1 }}</td>
                            <td>{{ $k->judul }}</td>
                            <td>
                                @if ($k->tipe === 'pemasukan')
                                    <span class="badge bg-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($k->jumlah, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d F Y') }}</td>
                            <td>{{ $k->keterangan ?: '-' }}</td>
                            <td>
                                <a href="{{ route('app.keuangan.detail', ['id' => $k->id]) }}"
                                    class="btn btn-sm btn-info">Detail</a>
                                <button wire:click="prepareEdit({{ $k->id }})"
                                    class="btn btn-sm btn-warning">Edit</button>
                                <button wire:click="prepareDelete({{ $k->id }})"
                                    class="btn btn-sm btn-danger">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $keuangans->links() }}
            </div>
        </div>
    </div>

    {{-- 📦 Modals --}}
    @include('components.modals.keuangan.add')
    @include('components.modals.keuangan.edit')
    @include('components.modals.keuangan.delete')

    {{-- 📈 Script ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chart = null;

            const renderChart = (pemasukan, pengeluaran) => {
                const options = {
                    chart: {
                        type: 'donut',
                        height: 250
                    },
                    series: [pemasukan, pengeluaran],
                    labels: ['Pemasukan', 'Pengeluaran'],
                    colors: ['#28a745', '#dc3545'],
                    legend: {
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: true
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Transaksi',
                                        formatter: () => pemasukan + pengeluaran
                                    }
                                }
                            }
                        }
                    },
                };

                if (chart) {
                    chart.updateSeries([pemasukan, pengeluaran]);
                } else {
                    chart = new ApexCharts(document.querySelector("#keuanganChart"), options);
                    chart.render();
                }
            };

            // Render pertama
            renderChart(
                parseInt(document.getElementById('chart-pemasukan').value),
                parseInt(document.getElementById('chart-pengeluaran').value)
            );

            // Update setiap Livewire morph
            Livewire.hook('morph.updated', () => {
                const pemasukan = parseInt(document.getElementById('chart-pemasukan').value);
                const pengeluaran = parseInt(document.getElementById('chart-pengeluaran').value);
                renderChart(pemasukan, pengeluaran);
            });
        });
    </script>
</div>
