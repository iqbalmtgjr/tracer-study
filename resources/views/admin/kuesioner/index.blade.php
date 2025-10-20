<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Kuesioner</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kuesioner</h3>
                            <div class="card-tools">
                                <button wire:click="openCreateModal" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Kuesioner
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Cari Kuesioner...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Judul</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Jumlah Responden</th>
                                            <th width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kuesioner as $index => $kues)
                                            <tr>
                                                <td>{{ $kuesioner->firstItem() + $index }}</td>
                                                <td>
                                                    <strong>{{ $kues->judul }}</strong>
                                                    @if ($kues->deskripsi)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($kues->deskripsi, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $kues->tanggal_mulai->format('d/m/Y') }} -
                                                    {{ $kues->tanggal_selesai->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    @if ($kues->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $kues->responden->count() }} Responden</td>
                                                <td>
                                                    <button wire:click="openEditModal({{ $kues->id }})"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click="toggleStatus({{ $kues->id }})"
                                                        class="btn btn-info btn-sm" title="Toggle Status">
                                                        <i
                                                            class="fas fa-toggle-{{ $kues->is_active ? 'on' : 'off' }}"></i>
                                                    </button>
                                                    <a href="{{ route('admin.pertanyaan.index', $kues->id) }}"
                                                        class="btn btn-success btn-sm"
                                                        title="Kelola Pertanyaan & Jawaban">
                                                        <i class="fas fa-question-circle"></i>
                                                    </a>
                                                    <button wire:click="confirmDelete({{ $kues->id }})"
                                                        class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $kuesioner->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Form Create/Edit -->
    <div class="modal fade" id="formModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Kuesioner</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Judul Kuesioner <span class="text-danger">*</span></label>
                            <input type="text" wire:model="judul"
                                class="form-control @error('judul') is-invalid @enderror" placeholder="Judul kuesioner">
                            @error('judul')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                                placeholder="Deskripsi kuesioner (opsional)"></textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="tanggal_mulai"
                                        class="form-control @error('tanggal_mulai') is-invalid @enderror">
                                    @error('tanggal_mulai')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="tanggal_selesai"
                                        class="form-control @error('tanggal_selesai') is-invalid @enderror">
                                    @error('tanggal_selesai')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" wire:model="is_active" class="custom-control-input"
                                    id="is_active">
                                <label class="custom-control-label" for="is_active">Aktifkan Kuesioner</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kuesioner ini?</p>
                    <p class="text-warning"><small>Perhatian: Kuesioner yang sudah memiliki responden tidak dapat
                            dihapus.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="delete" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('show-form-modal', () => {
                    $('#formModal').modal('show');
                });

                Livewire.on('hide-form-modal', () => {
                    $('#formModal').modal('hide');
                });

                Livewire.on('show-delete-modal', () => {
                    $('#deleteModal').modal('show');
                });

                Livewire.on('hide-delete-modal', () => {
                    $('#deleteModal').modal('hide');
                });
            });
        </script>
    @endpush
</div>
