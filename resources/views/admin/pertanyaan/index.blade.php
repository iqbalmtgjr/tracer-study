<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Pertanyaan & Jawaban</h1>
                    <small>Kuesioner: {{ $kuesioner->judul }}</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.kuesioner.index') }}">Kuesioner</a></li>
                        <li class="breadcrumb-item active">Pertanyaan</li>
                    </ol>
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
                            <h3 class="card-title">Daftar Pertanyaan</h3>
                            <div class="card-tools">
                                <button wire:click="openCreateModal" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Pertanyaan
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Cari Pertanyaan...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Pertanyaan</th>
                                            <th>Tipe</th>
                                            <th>Wajib</th>
                                            <th>Opsi Jawaban</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($pertanyaans) --}}
                                        @if ($pertanyaans)
                                            @forelse ($pertanyaans as $index => $p)
                                                <tr>
                                                    <td>{{ $pertanyaans->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong>{{ Str::limit($p->pertanyaan, 100) }}</strong>
                                                        @if ($p->kondisi_tampil)
                                                            <br><small class="text-muted">Kondisi:
                                                                {{ $p->kondisi_tampil }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- <span
                                                            class="badge badge-info">{{ ucfirst($p->tipe_pertanyaan) }}</span> --}}
                                                        @if ($p->tipe_pertanyaan === 'select')
                                                            <span class="badge badge-info">Pilihan Ganda</span>
                                                        @else
                                                            <span class="badge badge-info">Essay</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($p->is_required)
                                                            <span class="badge badge-danger">Ya</span>
                                                        @else
                                                            <span class="badge badge-secondary">Tidak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($p->tipe_pertanyaan === 'select')
                                                            {{ $p->opsiJawaban ? $p->opsiJawaban->count() : 0 }} opsi
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button wire:click="openEditModal({{ $p->id }})"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button wire:click="confirmDelete({{ $p->id }})"
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
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center text-danger">Error: Data
                                                    pertanyaan tidak dapat dimuat.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                @if ($pertanyaans)
                                    {{ $pertanyaans->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Form Create/Edit -->
    <div class="modal fade" id="formModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Pertanyaan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pertanyaan <span class="text-danger">*</span></label>
                            <textarea wire:model="pertanyaan" class="form-control @error('pertanyaan') is-invalid @enderror" rows="3"
                                placeholder="Tuliskan pertanyaan..."></textarea>
                            @error('pertanyaan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipe Pertanyaan <span class="text-danger">*</span></label>
                                    <select wire:model.live="tipe_pertanyaan"
                                        class="form-control @error('tipe_pertanyaan') is-invalid @enderror">
                                        <option value="select">Pilihan Ganda</option>
                                        <option value="textarea">Essay</option>
                                    </select>
                                    @error('tipe_pertanyaan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Urutan</label>
                                    <input type="number" wire:model="urutan"
                                        class="form-control @error('urutan') is-invalid @enderror" min="0">
                                    @error('urutan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kondisi Tampil</label>
                                    <input type="text" wire:model="kondisi_tampil"
                                        class="form-control @error('kondisi_tampil') is-invalid @enderror"
                                        placeholder="Opsional">
                                    @error('kondisi_tampil')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" wire:model="is_required" class="custom-control-input"
                                    id="is_required">
                                <label class="custom-control-label" for="is_required">Pertanyaan Wajib Dijawab</label>
                            </div>
                        </div>

                        <!-- Opsi Jawaban -->
                        @if ($tipe_pertanyaan === 'select')
                            <div class="form-group">
                                <label>Opsi Jawaban</label>
                                <div id="opsi-container">
                                    @if (is_array($opsiJawaban))
                                        @foreach ($opsiJawaban as $index => $opsi)
                                            <div class="input-group mb-2">
                                                <input type="text"
                                                    wire:model="opsiJawaban.{{ $index }}.opsi"
                                                    class="form-control" placeholder="Opsi jawaban">
                                                <input type="number"
                                                    wire:model="opsiJawaban.{{ $index }}.nilai"
                                                    class="form-control" placeholder="Nilai (opsional)"
                                                    style="max-width: 120px;">
                                                <div class="input-group-append">
                                                    <button type="button"
                                                        wire:click="removeOpsi({{ $index }})"
                                                        class="btn btn-danger" title="Hapus Opsi">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" wire:click="addOpsi" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Opsi
                                </button>
                            </div>
                        @endif
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
                    <p>Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
                    <p class="text-warning"><small>Perhatian: Pertanyaan yang sudah memiliki jawaban tidak dapat
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
