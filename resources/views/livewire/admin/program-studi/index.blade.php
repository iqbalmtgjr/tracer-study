<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Program Studi</h1>
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
                            <h3 class="card-title">Daftar Program Studi</h3>
                            <div class="card-tools">
                                <button wire:click="openCreateModal" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Program Studi
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Cari Program Studi...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama Program Studi</th>
                                            <th>Jumlah Alumni</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($programStudi as $index => $prodi)
                                            <tr>
                                                <td>{{ $programStudi->firstItem() + $index }}</td>
                                                <td>{{ $prodi->nama_prodi }}</td>
                                                <td>{{ $prodi->alumni->count() }} Alumni</td>
                                                <td>
                                                    <button wire:click="openEditModal({{ $prodi->id }})"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click="confirmDelete({{ $prodi->id }})"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $programStudi->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Form Create/Edit -->
    <div class="modal fade" id="formModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Program Studi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Program Studi <span class="text-danger">*</span></label>
                            <input type="text" wire:model="nama_prodi"
                                class="form-control @error('nama_prodi') is-invalid @enderror"
                                placeholder="Contoh: Pendidikan Matematika">
                            @error('nama_prodi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
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
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
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
