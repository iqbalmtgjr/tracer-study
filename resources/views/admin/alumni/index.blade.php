<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Alumni</h1>
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
                            <h3 class="card-title">Daftar Alumni</h3>
                            <div class="card-tools">
                                <button wire:click="openCreateModal" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Alumni
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Cari Alumni...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>NIM</th>
                                            <th>Nama Lengkap</th>
                                            <th>Program Studi</th>
                                            <th>Tahun Lulus</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($alumni as $index => $alum)
                                            <tr>
                                                <td>{{ $alumni->firstItem() + $index }}</td>
                                                <td>{{ $alum->nim }}</td>
                                                <td>{{ $alum->nama_lengkap }}</td>
                                                <td>{{ $alum->programStudi->nama_prodi }}</td>
                                                <td>{{ $alum->tahun_lulus }}</td>
                                                <td>
                                                    <button wire:click="openEditModal({{ $alum->id }})"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click="confirmDelete({{ $alum->id }})"
                                                        class="btn btn-danger btn-sm">
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
                                {{ $alumni->links() }}
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
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Alumni</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIM <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nim"
                                        class="form-control @error('nim') is-invalid @enderror"
                                        placeholder="Contoh: 1234567890">
                                    @error('nim')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nama_lengkap"
                                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        placeholder="Nama lengkap alumni">
                                    @error('nama_lengkap')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select wire:model="jenis_kelamin"
                                        class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Program Studi <span class="text-danger">*</span></label>
                                    <select wire:model="program_studi_id"
                                        class="form-control @error('program_studi_id') is-invalid @enderror">
                                        <option value="">Pilih Program Studi</option>
                                        @foreach ($programStudi as $prodi)
                                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    </select>
                                    @error('program_studi_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="tempat_lahir"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        placeholder="Tempat lahir">
                                    @error('tempat_lahir')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="tanggal_lahir"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror">
                                    @error('tanggal_lahir')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input type="text" wire:model="no_hp"
                                        class="form-control @error('no_hp') is-invalid @enderror"
                                        placeholder="Contoh: 081234567890">
                                    @error('no_hp')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" wire:model="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="alumni@example.com">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Lulus <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="tahun_lulus"
                                        class="form-control @error('tahun_lulus') is-invalid @enderror"
                                        placeholder="Contoh: 2023">
                                    @error('tahun_lulus')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>IPK</label>
                                    <input type="number" step="0.01" min="0" max="4"
                                        wire:model="ipk" class="form-control @error('ipk') is-invalid @enderror"
                                        placeholder="Contoh: 3.75">
                                    @error('ipk')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3"
                                placeholder="Alamat lengkap"></textarea>
                            @error('alamat')
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
                    <p>Apakah Anda yakin ingin menghapus data alumni ini?</p>
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
