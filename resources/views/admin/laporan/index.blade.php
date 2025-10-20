<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan & Statistik</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pilih Kuesioner</label>
                                <select wire:model.live="selectedKuesioner" class="form-control">
                                    <option value="">Pilih Kuesioner</option>
                                    @foreach ($kuesioner as $kues)
                                        <option value="{{ $kues->id }}">{{ $kues->judul }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Program Studi</label>
                                <select wire:model.live="filterProgramStudi" class="form-control">
                                    <option value="">Semua Program Studi</option>
                                    @foreach ($programStudi as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tahun Lulus</label>
                                <select wire:model.live="filterTahunLulus" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($tahunLulus as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select wire:model.live="filterStatus" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($selectedKuesioner)
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $statistik['total_responden'] }}</h3>
                                <p>Total Responden</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $statistik['responden_selesai'] }}</h3>
                                <p>Responden Selesai</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $statistik['responden_draft'] }}</h3>
                                <p>Responden Draft</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $statistik['persentase_kelulusan'] }}%</h3>
                                <p>Tingkat Kelulusan</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-percent"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Responden per Program Studi</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="programStudiChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Responden per Tahun Lulus</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="tahunLulusChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Responden</h3>
                        <div class="card-tools">
                            <button wire:click="exportExcel" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                            <button wire:click="exportPdf" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Alumni</th>
                                        <th>Program Studi</th>
                                        <th>Tahun Lulus</th>
                                        <th>Status</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporanData as $index => $responden)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $responden->alumni->nim }}</td>
                                            <td>{{ $responden->alumni->nama_lengkap }}</td>
                                            <td>{{ $responden->alumni->programStudi->nama_prodi }}</td>
                                            <td>{{ $responden->alumni->tahun_lulus }}</td>
                                            <td>
                                                @if ($responden->status == 'selesai')
                                                    <span class="badge badge-success">Selesai</span>
                                                @else
                                                    <span class="badge badge-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td>{{ $responden->tanggal_mulai ? $responden->tanggal_mulai->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td>{{ $responden->tanggal_selesai ? $responden->tanggal_selesai->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data responden</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-muted">Pilih kuesioner terlebih dahulu untuk melihat laporan</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('footer')
        <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
        <script>
            document.addEventListener('livewire:updated', function() {
                // Chart untuk Program Studi
                const programStudiCtx = document.getElementById('programStudiChart');
                if (programStudiCtx) {
                    const programStudiData = @json($chartData['program_studi']);
                    new Chart(programStudiCtx, {
                        type: 'bar',
                        data: {
                            labels: programStudiData.map(item => item.nama),
                            datasets: [{
                                label: 'Jumlah Responden',
                                data: programStudiData.map(item => item.total),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Chart untuk Tahun Lulus
                const tahunLulusCtx = document.getElementById('tahunLulusChart');
                if (tahunLulusCtx) {
                    const tahunLulusData = @json($chartData['tahun_lulus']);
                    new Chart(tahunLulusCtx, {
                        type: 'line',
                        data: {
                            labels: tahunLulusData.map(item => item.tahun),
                            datasets: [{
                                label: 'Jumlah Responden',
                                data: tahunLulusData.map(item => item.total),
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</div>
