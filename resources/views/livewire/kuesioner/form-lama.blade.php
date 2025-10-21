<div>
    <!-- Hero Section -->
    <div class="container-fluid"
        style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.95), rgba(13, 110, 253, 0.85)); padding: 80px 0 40px 0;">
        <div class="container">
            <div class="text-center text-white">
                <h2 class="display-5 mb-3">Kuesioner Tracer Study</h2>
                <p class="lead">Silakan isi kuesioner berikut untuk membantu kami meningkatkan kualitas pendidikan</p>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($kuesioner)
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white py-4">
                            <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>{{ $kuesioner->judul }}</h4>
                            @if ($kuesioner->deskripsi)
                                <p class="mb-0 mt-2 small opacity-75">{{ $kuesioner->deskripsi }}</p>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <form wire:submit.prevent="submit">
                                @foreach ($pertanyaan as $index => $p)
                                    @if ($this->shouldShowQuestion($p))
                                        <div class="mb-5 pb-4 border-bottom">
                                            <label class="form-label fw-bold fs-5 mb-3">
                                                <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                                {{ $p->pertanyaan }}
                                                @if ($p->is_required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            @if ($p->keterangan)
                                                <small class="text-muted d-block mb-3">
                                                    <i class="fas fa-info-circle me-1"></i>{{ $p->keterangan }}
                                                </small>
                                            @endif

                                            {{-- Text Input --}}
                                            @if ($p->tipe_pertanyaan === 'text')
                                                <input type="text" wire:model="jawaban.{{ $p->kode_pertanyaan }}"
                                                    class="form-control form-control-lg"
                                                    placeholder="Ketik jawaban Anda..."
                                                    {{ $p->is_required ? 'required' : '' }}>
                                            @endif

                                            {{-- Textarea --}}
                                            @if ($p->tipe_pertanyaan === 'textarea')
                                                <textarea wire:model="jawaban.{{ $p->kode_pertanyaan }}" class="form-control" rows="4"
                                                    placeholder="Ketik jawaban Anda..." {{ $p->is_required ? 'required' : '' }}></textarea>
                                            @endif

                                            {{-- Number Input --}}
                                            @if ($p->tipe_pertanyaan === 'number')
                                                <input type="number" wire:model="jawaban.{{ $p->kode_pertanyaan }}"
                                                    class="form-control form-control-lg" placeholder="Masukkan angka..."
                                                    {{ $p->is_required ? 'required' : '' }}>
                                            @endif

                                            {{-- Date Input --}}
                                            @if ($p->tipe_pertanyaan === 'date')
                                                <input type="date" wire:model="jawaban.{{ $p->kode_pertanyaan }}"
                                                    class="form-control form-control-lg"
                                                    {{ $p->is_required ? 'required' : '' }}>
                                            @endif

                                            {{-- Radio Button --}}
                                            @if ($p->tipe_pertanyaan === 'radio')
                                                <div class="ms-3">
                                                    @foreach ($p->opsiJawaban as $opsi)
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="radio"
                                                                wire:model.live="jawaban.{{ $p->kode_pertanyaan }}"
                                                                value="{{ $opsi->kode_opsi }}"
                                                                id="opsi_{{ $opsi->id }}"
                                                                {{ $p->is_required ? 'required' : '' }}>
                                                            <label class="form-check-label"
                                                                for="opsi_{{ $opsi->id }}">
                                                                {{ $opsi->opsi }}
                                                            </label>
                                                        </div>

                                                        {{-- Input tambahan jika memilih "Lainnya" --}}
                                                        @if ($opsi->has_input && isset($jawaban[$p->kode_pertanyaan]) && $jawaban[$p->kode_pertanyaan] == $opsi->kode_opsi)
                                                            <div class="ms-4 mb-3">
                                                                <input type="{{ $opsi->input_type }}"
                                                                    wire:model="jawaban.{{ $p->kode_pertanyaan }}_text"
                                                                    class="form-control" placeholder="Sebutkan..."
                                                                    required>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Checkbox --}}
                                            @if ($p->tipe_pertanyaan === 'checkbox')
                                                <div class="ms-3">
                                                    @foreach ($p->opsiJawaban as $opsi)
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                wire:model="jawaban.{{ $p->kode_pertanyaan }}"
                                                                value="{{ $opsi->kode_opsi }}"
                                                                id="opsi_{{ $opsi->id }}">
                                                            <label class="form-check-label"
                                                                for="opsi_{{ $opsi->id }}">
                                                                {{ $opsi->opsi }}
                                                            </label>
                                                        </div>

                                                        {{-- Input tambahan jika memilih "Lainnya" --}}
                                                        @if (
                                                            $opsi->has_input &&
                                                                isset($jawaban[$p->kode_pertanyaan]) &&
                                                                in_array($opsi->kode_opsi, $jawaban[$p->kode_pertanyaan] ?? []))
                                                            <div class="ms-4 mb-3">
                                                                <input type="{{ $opsi->input_type }}"
                                                                    wire:model="jawaban.{{ $p->kode_pertanyaan }}_text"
                                                                    class="form-control" placeholder="Sebutkan..."
                                                                    required>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Select Dropdown --}}
                                            @if ($p->tipe_pertanyaan === 'select')
                                                <select wire:model="jawaban.{{ $p->kode_pertanyaan }}"
                                                    class="form-select form-select-lg"
                                                    {{ $p->is_required ? 'required' : '' }}>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($p->opsiJawaban as $opsi)
                                                        <option value="{{ $opsi->kode_opsi }}">{{ $opsi->opsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            {{-- Grid/Matrix --}}
                                            @if ($p->tipe_pertanyaan === 'grid')
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Kompetensi</th>
                                                                <th colspan="5" class="text-center">A. Pada Saat
                                                                    Lulus
                                                                </th>
                                                                <th colspan="5" class="text-center">B. Saat Ini di
                                                                    Pekerjaan</th>
                                                            </tr>
                                                            <tr>
                                                                <th></th>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <th class="text-center">{{ $i }}</th>
                                                                @endfor
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <th class="text-center">{{ $i }}</th>
                                                                @endfor
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($p->pertanyaanGrid as $grid)
                                                                <tr>
                                                                    <td>{{ $grid->row_label }}</td>
                                                                    {{-- Kolom A --}}
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <td class="text-center">
                                                                            <input type="radio"
                                                                                wire:model="jawaban.{{ $grid->kode_row }}_a"
                                                                                value="{{ $i }}"
                                                                                name="{{ $grid->kode_row }}_a"
                                                                                required>
                                                                        </td>
                                                                    @endfor
                                                                    {{-- Kolom B --}}
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <td class="text-center">
                                                                            <input type="radio"
                                                                                wire:model="jawaban.{{ $grid->kode_row }}_b"
                                                                                value="{{ $i }}"
                                                                                name="{{ $grid->kode_row }}_b"
                                                                                required>
                                                                        </td>
                                                                    @endfor
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Keterangan: 1 = Sangat Rendah, 5 = Sangat Tinggi
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="/" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Kuesioner
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
