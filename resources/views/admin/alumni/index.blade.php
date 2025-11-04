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
                                <button wire:click="openCreateModal" class="btn btn-info shadow mr-2">
                                    <i class="fas fa-plus mr-1"></i> Tambah Alumni
                                </button>

                                <button wire:click="openImportModal" class="btn btn-dark shadow">
                                    <i class="fas fa-file-import mr-1"></i> Import Alumni
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

                            <div class="table-responsive shadow-sm rounded">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>NIM</th>
                                            <th>Nama Lengkap</th>
                                            <th>Nama Prodi</th>
                                            <th class="text-center">Tahun Lulus</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($alumni as $index => $alum)
                                            <tr>
                                                <td class="text-center">{{ $alumni->firstItem() + $index }}</td>
                                                <td>{{ $alum->nim }}</td>
                                                <td>{{ $alum->nama_lengkap }}</td>
                                                <td>{{ $alum->programstudi->program_studi }}</td>
                                                <td class="text-center">{{ $alum->tahun_lulus }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button wire:click="openEditModal({{ $alum->id }})"
                                                            class="btn btn-warning" title="Edit Data">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button wire:click="confirmDelete({{ $alum->id }})"
                                                            class="btn btn-danger" title="Hapus Data">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i> Tidak ada data
                                                    alumni yang ditemukan.
                                                </td>
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
                                    <label>NIK <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nik"
                                        class="form-control @error('nik') is-invalid @enderror"
                                        placeholder="Contoh: 1111222233333">
                                    @error('nik')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIM <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nim"
                                        class="form-control @error('nim') is-invalid @enderror"
                                        placeholder="Contoh: 123123">
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
                                    <label>Program Studi <span class="text-danger">*</span></label>
                                    <select wire:model="kodeprodi"
                                        class="form-control @error('kodeprodi') is-invalid @enderror">
                                        <option value="">Pilih Program Studi</option>
                                        @foreach ($programStudi as $prodi)
                                            <option value="{{ $prodi->kode_program_studi }}">
                                                {{ $prodi->program_studi }}</option>
                                        @endforeach
                                    </select>
                                    @error('kodeprodi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="row">
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

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Alumni</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="import">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih File Excel <span class="text-danger">*</span></label>
                            <input type="file" wire:model="importFile"
                                class="form-control @error('importFile') is-invalid @enderror" accept=".xlsx,.xls">

                            <!-- Loading Indicator -->
                            <div wire:loading wire:target="importFile" class="text-info mt-2">
                                <i class="fas fa-spinner fa-spin"></i> Mengupload file...
                            </div>

                            @error('importFile')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                File harus berformat .xlsx atau .xls
                            </small>
                        </div>

                        <!-- Preview nama file -->
                        @if ($importFile)
                            <div class="alert alert-info">
                                <strong>File:</strong> {{ $importFile->getClientOriginalName() }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled"
                            wire:target="import">
                            <span wire:loading.remove wire:target="import">
                                <i class="fas fa-upload"></i> Import
                            </span>
                            <span wire:loading wire:target="import">
                                <i class="fas fa-spinner fa-spin"></i> Mengimpor...
                            </span>
                        </button>
                    </div>
                </form>
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

                Livewire.on('show-import-modal', () => {
                    $('#importModal').modal('show');
                });

                Livewire.on('hide-import-modal', () => {
                    $('#importModal').modal('hide');
                });
            });
        </script>
    @endpush
</div>
