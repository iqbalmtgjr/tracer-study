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

                            <div class="table-responsive shadow-sm">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th>Pertanyaan</th>
                                            <th width="15%" class="text-center">Tipe Pertanyaan</th>
                                            <th width="12%" class="text-center">Kode Pertanyaan</th>
                                            <th width="12%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($pertanyaans) --}}
                                        @if ($pertanyaans)
                                        @forelse ($pertanyaans as $index => $p)
                                        <tr>
                                            <td class="text-center font-weight-bold">{{ $pertanyaans->firstItem() + $index }}</td>
                                            <td>
                                                <div class="mb-1">
                                                    <strong class="text-dark">{{ Str::limit($p->text, 100) }}</strong>
                                                </div>
                                                @if ($p->kondisi_tampil)
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    <strong>Kondisi:</strong> {{ $p->kondisi_tampil }}
                                                </small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-primary badge-pill px-3 py-2">
                                                    {{ $p->input_type }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <code class="bg-light px-2 py-1 rounded">{{ $p->question_code }}</code>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button wire:click="openEditModal({{ $p->id }})"
                                                        class="btn btn-warning btn-sm"
                                                        title="Edit"
                                                        data-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if(in_array($p->input_type, ['select', 'radio', 'checkbox']) && !in_array($p->question_code, ['f5a1', 'f5a2']))
                                                    <button wire:click="openEditOpsiModal({{ $p->id }})"
                                                        class="btn btn-warning btn-sm"
                                                        title="Opsi Jawaban"
                                                        data-toggle="tooltip">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    @endif
                                                    <button wire:click="confirmDelete({{ $p->id }})"
                                                        class="btn btn-danger btn-sm"
                                                        title="Hapus"
                                                        data-toggle="tooltip">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">Tidak ada data pertanyaan</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                        @else
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                <p class="text-danger mb-0 font-weight-bold">Error: Data pertanyaan tidak dapat dimuat.</p>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <style>
                                .table-hover tbody tr:hover {
                                    background-color: #f8f9fa;
                                }

                                .btn-group .btn {
                                    margin: 0 2px;
                                }

                                .shadow-sm {
                                    box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
                                }
                            </style>

                            <script>
                                // Aktifkan tooltip Bootstrap
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>

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
                            <textarea wire:model="text" class="form-control @error('text') is-invalid @enderror" rows="3"
                                placeholder="Tuliskan pertanyaan..."></textarea>
                            @error('text')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="conditional">Pertanyaan syarat<span class="text-danger">*</span></label>
                            <select wire:model.live="parentCcode" id="conditional"
                                class="form-control @error('parentCcode') is-invalid @enderror">
                                <option value=""> --Pilih-- </option>
                                @foreach($this->tanyas as $tanya)
                                <option value="{{ $tanya->question_code }}" {{ $parentCcode == $tanya->question_code ? 'selected' : '' }}>{{ $tanya->text }}</option>
                                @endforeach
                            </select>
                            @error('parentCcode')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="parentValue">Opsi Pertanyaan Syarat<span class="text-danger">*</span></label>
                            <select wire:model.live="parentValue" id="parentValue"
                                class="form-control @error('parentValue') is-invalid @enderror"
                                @if(empty($this->opsiJawaban)) disabled @endif>
                                <option value=""> --Pilih-- </option>
                                @foreach($this->opsiJawaban as $opsi)
                                <option value="{{ $opsi->value }}" {{ $parentValue == $opsi->value ? 'selected' : '' }}>{{ $opsi->label }}</option>
                                @endforeach
                            </select>
                            @error('parentValue')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipe Pertanyaan <span class="text-danger">*</span></label>
                                    <select wire:model="input_type"
                                        class="form-control @error('input_type') is-invalid @enderror">

                                        @foreach($semuaType as $tipe => $anjai)
                                        <option value="{{ $tipe }}">{{ $tipe }}</option>
                                        @endforeach
                                    </select>
                                    @error('input_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kode Pertanyaan</label>
                                    <input type="text" wire:model="question_code"
                                        class="form-control @error('question_code') is-invalid @enderror">
                                    @error('question_code')
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

    <!-- Modal Form Create/Edit Opsi Jawaban -->
    <div class="modal fade" id="formOpsiModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $this->text }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Label</th>
                                    <th width="20%">Value</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($opsiJawaban as $index => $opsi)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $opsi->label }}</td>
                                    <td>{{ $opsi->value }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" wire:click="editOpsi({{ $opsi->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="deleteOpsi({{ $opsi->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada opsi jawaban
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <di class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Label Jawaban</label>
                                    <input type="text" wire:model="opsi_label"
                                        class="form-control @error('opsi_label') is-invalid @enderror">
                                    @error('opsi_label')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Value Jawaban</label>
                                    <input type="number" wire:model="maxValue"
                                        class="form-control @error('maxValue') is-invalid @enderror">
                                    @error('maxValue')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Is Custom Input?</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                            id="isCustomInput"
                                            wire:model="is_custom_input">
                                        <label class="custom-control-label" for="isCustomInput">
                                            {{ $is_custom_input ? 'Ya' : 'Tidak' }}
                                        </label>
                                    </div>
                                    @error('is_custom_input')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </di>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
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
            Livewire.on('show-form-modal-opsi', () => {
                $('#formOpsiModal').modal('show');
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