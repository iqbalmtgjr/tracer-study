<div>
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
                {{-- Card Data Diri Alumni --}}
                @if (session()->get('alumni_data'))
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white py-4">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Data Diri</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small mb-1">NIK</label>
                                <p class="fw-semibold mb-0">{{ $alumniData['nik'] ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small mb-1">NIM</label>
                                <p class="fw-semibold mb-0">{{ $alumniData['nim'] ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small mb-1">Nama</label>
                                <p class="fw-semibold mb-0">{{ $alumniData['nama'] ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small mb-1">Program Studi</label>
                                <p class="fw-semibold mb-0">{{ $alumniData['nama_prodi'] ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small mb-1">Tahun Lulus</label>
                                <p class="fw-semibold mb-0">{{ $alumniData['tahun_lulus'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Notifikasi Sukses --}}
                @if (session()->has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Notifikasi Error (jika ada error umum) --}}
                @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white py-4">
                        <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Formulir Kuesioner Alumni</h4>
                    </div>

                    {{-- Penomoran pertanyaan yang ditampilkan secara berurutan --}}
                    @php
                    $questionNumber = 0;
                    @endphp
                    <div class="card-body p-4">
                        <form wire:submit.prevent="submitForm">
                            @foreach ($questions as $index => $question)
                            {{-- Gunakan shouldShow() dari Livewire Class untuk percabangan --}}
                            @if ($this->shouldShow($question))
                            {{-- Inkremen penomorannya di sini, HANYA jika pertanyaan ditampilkan --}}
                            @php
                            $questionNumber++;
                            @endphp
                            <div class="mb-5 pb-4 border-bottom">
                                <label class="form-label fw-bold fs-5 mb-3">
                                    {{-- Gunakan $questionNumber --}}
                                    <span class="badge bg-primary me-2">{{ $questionNumber }}</span>

                                    {{ $question->text }}
                                    {{-- Simbol wajib diisi tetap ada --}}
                                    <span class="text-danger">*</span>
                                </label>
                                {{-- 3. Text Input --}}
                                @if ($question->input_type === 'text')
                                <input type="text" wire:model="answers.{{ $question->question_code }}"
                                    class="form-control form-control-lg" placeholder="Ketik jawaban Anda...">
                                @endif
                                {{-- 2. Number Input --}}
                                @if ($question->input_type === 'number')
                                @if ($question->question_code === 'f505bekerjawiraswasta')
                                {{-- Input untuk format rupiah --}}
                                <div class="input-group" wire:key="{{$question->question_code}}">
                                    <span class="input-group-text">Rp</span>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg"
                                        placeholder="0"
                                        x-data="{ 
                                            displayValue: '',
                                            formatRupiah(angka) {
                                                let number_string = angka.replace(/[^,\d]/g, '').toString();
                                                let split = number_string.split(',');
                                                let sisa = split[0].length % 3;
                                                let rupiah = split[0].substr(0, sisa);
                                                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                                                
                                                if (ribuan) {
                                                    let separator = sisa ? '.' : '';
                                                    rupiah += separator + ribuan.join('.');
                                                }
                                                
                                                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                                                return rupiah;
                                            },
                                            updateValue() {
                                                // Hapus format, simpan hanya angka ke Livewire
                                                let numericValue = this.displayValue.replace(/\./g, '').replace(/,/g, '.');
                                                @this.set('answers.{{ $question->question_code }}', numericValue);
                                            }
                                        }"
                                        x-model="displayValue"
                                        x-on:input="displayValue = formatRupiah($event.target.value); updateValue()"
                                        x-init="displayValue = @js($answers[$question->question_code] ?? ''); if(displayValue) displayValue = formatRupiah(displayValue.toString())">
                                </div>
                                @else
                                {{-- Input number biasa --}}
                                <input
                                    type="number"
                                    wire:model="answers.{{ $question->question_code }}"
                                    class="form-control form-control-lg"
                                    placeholder="Masukkan angka..."
                                    min="0">
                                @endif
                                @endif

                                {{-- 4. Select Dropdown (INPUT BARU) --}}
                                @if ($question->input_type === 'select')
                                @if($question->question_code == 'f5a1')
                                <select wire:model.live="answers.{{ $question->question_code }}"
                                    class="form-select form-select-lg"
                                    id="{{ $question->question_code }}">

                                    <option value="">-- Silakan Pilih --</option>

                                    @foreach ($provinsis as $option)
                                    <option value="{{ $option->kode_provinsi }}">{{ $option->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                                @endif
                                @if($question->question_code == 'f5a2')
                                <select wire:model="answers.{{ $question->question_code }}"
                                    class="form-select form-select-lg"
                                    id="{{ $question->question_code }}">

                                    <option value="">-- Silakan Pilih --</option>

                                    @foreach ($kabupatens as $option)
                                    <option value="{{ $option->kode_kabupaten_kota }}">{{ $option->nama_kabupaten_kota }}</option>
                                    @endforeach
                                </select>
                                @endif
                                @if($question->question_code != 'f5a1' && $question->question_code !== 'f5a2')
                                <select wire:model="answers.{{ $question->question_code }}"
                                    class="form-select form-select-lg"
                                    id="{{ $question->question_code }}">

                                    <option value="">-- Silakan Pilih --</option>

                                    @foreach ($question->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->label }}</option>
                                    @endforeach
                                </select>
                                @endif
                                @endif

                                {{-- 1. Radio Button --}}
                                @if ($question->input_type === 'radio')
                                <div class="ms-3">
                                    @foreach ($question->options as $option)
                                    <div class="form-check mb-2" wire:key="{{ $option->id }}">
                                        <input class="form-check-input" type="radio"
                                            wire:model.live="answers.{{ $question->question_code }}"
                                            value="{{ $option->id }}"
                                            id="opsi_{{ $question->question_code }}_{{ $option->id }}">
                                        <label class="form-check-label"
                                            for="opsi_{{ $question->question_code }}_{{ $option->id }}">
                                            {{ $option->label }}
                                        </label>
                                    </div>
                                    {{-- Tampilkan input custom HANYA jika option ini dipilih DAN memiliki is_custom_input --}}
                                    @if($option->is_custom_input &&
                                    isset($answers[$question->question_code]) &&
                                    $answers[$question->question_code] == $option->id)
                                    <div class="ms-4 mb-3">
                                        <input type="{{ in_array($question->question_code, ['f301']) ? 'number' : 'text' }}"
                                            wire:model.live="answersLainnya.{{ $question->question_code }}"
                                            class="form-control" placeholder="Ketik jawaban lainnya...">

                                        {{-- Tampilkan error khusus untuk input lainnya --}}
                                        @php
                                        $errorKey = "answersLainnya.{$question->question_code}";
                                        @endphp
                                        @error($errorKey)
                                        <small class="text-danger mt-2 d-block">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                @endif
                                {{-- 6. Grid/Matrix --}}
                                @if ($question->input_type === 'grid')
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        {{-- ... THEAD (Header) SAMA SEPERTI KODE ANDA ... --}}
                                        <thead class="table-light">
                                            <tr>

                                                <th colspan="5" class="text-center">A. Pada Saat
                                                    Lulus
                                                </th>
                                                <th class="text-center">Kompetensi</th>
                                                <th colspan="5" class="text-center">B. Saat Ini di
                                                    Pekerjaan</th>
                                            </tr>
                                            <tr>

                                                @for ($i = 1; $i <= 5; $i++)
                                                    <th class="text-center">{{ $i }}</th>
                                                    @endfor
                                                    <th></th>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <th class="text-center">{{ $i }}</th>
                                                        @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            // Akses Getter baru: $this->gridRowsGrouped
                                            // $question->id adalah Question ID yang menjadi kunci grouping
                                            $gridRows = $this->gridRowsGrouped[$question->id] ?? collect();
                                            @endphp

                                            @foreach ($gridRows as $gridRow)
                                            @php
                                            $errorKeyA = "gridAnswers.{$question->question_code}.{$gridRow->row_code}_A";
                                            $errorKeyB = "gridAnswers.{$question->question_code}.{$gridRow->row_code}_B";
                                            $hasErrorA = $errors->has($errorKeyA);
                                            $hasErrorB = $errors->has($errorKeyB);
                                            @endphp
                                            <tr>
                                                {{-- Kolom A (Saat Lulus) --}}
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <td class="text-center {{ $hasErrorA ? 'bg-danger bg-opacity-10' : '' }}">
                                                    <input type="radio"
                                                        wire:model="gridAnswers.{{ $question->question_code }}.{{ $gridRow->row_code }}_A"
                                                        value="{{ $i }}">
                                                    </td>
                                                    @endfor

                                                    {{-- Teks Kompetensi --}}
                                                    <td class="text-center">{{ $gridRow->row_label }}</td>

                                                    {{-- Kolom B (Saat Ini di Pekerjaan) --}}
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <td class="text-center {{ $hasErrorB ? 'bg-danger bg-opacity-10' : '' }}">
                                                        <input type="radio"
                                                            wire:model="gridAnswers.{{ $question->question_code }}.{{ $gridRow->row_code }}_B"
                                                            value="{{ $i }}">

                                                        </td>
                                                        @endfor
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                {{-- 7. Checkbox (Jawaban Bisa Lebih dari Satu) --}}
                                @if ($question->input_type === 'checkbox')
                                <div class="ms-3">
                                    {{-- Tentukan kode pertanyaan saat ini --}}
                                    @php $currentCode = $question->question_code; @endphp

                                    @foreach ($question->options as $option)
                                    <div class="form-check mb-2" wire:key="option-{{ $currentCode }}-{{ $option->id }}">
                                        <input class="form-check-input" type="checkbox"
                                            {{-- 1. PERBAIKAN: Binding ke array yang sama --}}
                                            wire:model.live="answers.{{ $currentCode }}_{{ $option->id }}"
                                            value="{{ $option->value }}"
                                            id="opsi_{{ $currentCode }}_{{ $option->id }}">

                                        <label class="form-check-label"
                                            for="opsi_{{ $currentCode }}_{{ $option->id }}">
                                            {{ $option->label }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @if($option->is_custom_input && isset($answers[$currentCode.'_'.$option->id]) && $answers[$currentCode.'_'.$option->id])
                                <div class="ms-4 mb-3">
                                    <!-- <label class="form-label small text-muted">Sebutkan lainnya:</label> -->
                                    {{-- Gunakan wire:model ke properti answersLainnya yang baru --}}
                                    <input type="text"
                                        wire:model.live="answersLainnya.{{ $currentCode }}_{{ $option->id }}"
                                        class="form-control" placeholder="Ketik jawaban lainnya...">

                                    {{-- Tampilkan error khusus untuk input lainnya --}}
                                    @php
                                    $errorKey = "answersLainnya.{$currentCode}_{$option->id}";
                                    @endphp
                                    @error($errorKey)
                                    <small class="text-danger mt-2 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif
                                @endif
                                {{-- Tampilkan Error Validation --}}
                                @php
                                $errorKeyanswers = "answers.{$question->question_code}";
                                @endphp
                                @error($errorKeyanswers)
                                <small class="text-danger mt-2 d-block"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                            @endif
                            @endforeach
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ url('/') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Kuesioner
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- Notifikasi Sukses --}}
                    @if (session()->has('success_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- Notifikasi Error (jika ada error umum) --}}
                    @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>