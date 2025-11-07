<div class="mt-3">
    {{-- 📊 Statistik Keuangan --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Statistik Keuangan Kamu</h5>

            <div wire:loading wire:target="search,filterTipe" class="text-muted mb-2">
                <small>🔄 Memperbarui statistik...</small>
            </div>

            {{-- 🎯 Chart Area --}}
            <div id="keuanganChart" style="height: 300px; margin-top: 25px; margin-bottom: 20px;"></div>

            {{-- Hidden inputs untuk data chart --}}
            <input type="hidden" id="keuanganChart-pemasukan" value="{{ $totalPemasukan }}">
            <input type="hidden" id="keuanganChart-pengeluaran" value="{{ $totalPengeluaran }}">
        </div>
    </div>

    {{-- 💼 Daftar Transaksi --}}
    <div class="card shadow-sm">
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

            <div wire:loading wire:target="search,filterTipe" class="text-muted mb-2">
                <small>🔄 Memuat data...</small>
            </div>

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

            {{-- 📋 Tabel Transaksi --}}
            <table class="table table-striped align-middle">
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
                                @if ($k->tipe == 'pemasukan')
                                    <span class="badge bg-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($k->jumlah, 0, ',', '.') }}</td>
                            <td>{{ date('d F Y', strtotime($k->tanggal)) }}</td>
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

            <div class="mt-3">
                {{ $keuangans->links() }}
            </div>
        </div>
    </div>

    {{-- 📦 Modals --}}
    @include('components.modals.keuangan.add')
    @include('components.modals.keuangan.edit')
    @include('components.modals.keuangan.delete')

    {{-- 📈 Chart Script --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chartInstance = null;

            const renderChart = (pemasukan, pengeluaran) => {
                pemasukan = isNaN(pemasukan) ? 0 : pemasukan;
                pengeluaran = isNaN(pengeluaran) ? 0 : pengeluaran;
                const total = pemasukan + pengeluaran;

                const options = {
                    chart: {
                        type: 'donut',
                        height: 320,
                        offsetY: 15,
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 2,
                            blur: 4,
                            color: '#999',
                            opacity: 0.25
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 600,
                            animateGradually: {
                                enabled: true,
                                delay: 150
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 500
                            }
                        },
                        parentHeightOffset: 0,
                    },
                    grid: {
                        padding: {
                            top: 25,
                            bottom: 20
                        }
                    },
                    series: [pemasukan, pengeluaran],
                    labels: ['Pemasukan', 'Pengeluaran'],
                    colors: ['#28a745', '#dc3545'],
                    legend: {
                        position: 'bottom',
                        fontSize: '14px'
                    },
                    dataLabels: {
                        enabled: true
                    },
                    stroke: {
                        show: false
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '15px',
                                        offsetY: 10
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '22px',
                                        fontWeight: '600',
                                        offsetY: -20, // Angka sedikit naik
                                        formatter: (val) => 'Rp ' + Number(val).toLocaleString('id-ID')
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '15px',
                                        color: '#6c757d',
                                        offsetY: 80, // ⬇️ Label total turun ke bawah
                                        formatter: () => 'Rp ' + total.toLocaleString('id-ID')
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => 'Rp ' + Number(val).toLocaleString('id-ID')
                        }
                    }
                };

                const el = document.querySelector("#keuanganChart");
                if (!el) return;

                // 🔁 Update atau render ulang dengan animasi halus
                if (chartInstance) {
                    chartInstance.updateOptions(options);
                    chartInstance.updateSeries([pemasukan, pengeluaran]);
                } else {
                    chartInstance = new ApexCharts(el, options);
                    chartInstance.render();
                }
            };

            const refreshChart = () => {
                const pemasukan = parseInt(document.getElementById('keuanganChart-pemasukan')?.value ?? 0);
                const pengeluaran = parseInt(document.getElementById('keuanganChart-pengeluaran')?.value ?? 0);
                renderChart(pemasukan, pengeluaran);
            };

            // 🚀 Render pertama
            refreshChart();

            // 🔄 Update tiap kali Livewire selesai update DOM
            Livewire.hook('morph.updated', () => {
                setTimeout(refreshChart, 200);
            });
        });
    </script>
</div>
