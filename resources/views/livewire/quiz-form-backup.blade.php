<div>
    @assets
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endassets
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
                        <p class="mb-0 mt-2 small opacity-75">Bagian Wajib: Status Alumni dan Waktu Mendapatkan
                            Pekerjaan</p>
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

                                        {{-- ------------------------ --}}
                                        {{-- Render Input Berdasarkan Tipe (Kode SAMA seperti sebelumnya) --}}
                                        {{-- ------------------------ --}}

                                        {{-- 1. Radio Button --}}
                                        @if ($question->input_type === 'radio')
                                            <div class="ms-3">
                                                @foreach ($question->options as $option)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio"
                                                            wire:model.live="answers.{{ $question->question_code }}"
                                                            value="{{ $option->value }}"
                                                            id="opsi_{{ $question->question_code }}_{{ $option->value }}"
                                                            required>
                                                        <label class="form-check-label"
                                                            for="opsi_{{ $question->question_code }}_{{ $option->value }}">
                                                            {{ $option->label }}
                                                        </label>
                                                    </div>
                                                    {{-- === LOGIKA INPUT TEKS TAMBAHAN KHUSUS === --}}
                                                    {{-- Tampilkan input teks jika ini adalah pertanyaan f1101 DAN value terpilih adalah '5' --}}
                                                    @if (
                                                        $question->question_code === 'f1101' &&
                                                            isset($answers['f1101']) &&
                                                            $answers['f1101'] === $option->value &&
                                                            $option->value === '5')
                                                        <div class="ms-4 mb-3">
                                                            <!-- <label class="form-label small text-muted">Sebutkan lainnya:</label> -->
                                                            {{-- Gunakan wire:model ke properti answers_lainnya yang baru --}}
                                                            <input type="text"
                                                                wire:model="answers_lainnya.f1101_lainnya"
                                                                class="form-control"
                                                                placeholder="Ketik jawaban lainnya..." required>

                                                            {{-- Tampilkan error khusus untuk input lainnya --}}
                                                            @error('answers_lainnya.f1101_lainnya')
                                                                <small
                                                                    class="text-danger mt-2 d-block">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                    @if (in_array($question->question_code, ['f1201', 'f1201_belum_bekerja', 'f1201_wiraswasta', 'f1201_mencari_kerja']) &&
                                                            isset($answers[$question->question_code]) &&
                                                            $answers[$question->question_code] === $option->value &&
                                                            $option->value === '7')
                                                        <div class="ms-4 mb-3">
                                                            <!-- <label class="form-label small text-muted">Sebutkan lainnya:</label> -->
                                                            {{-- Gunakan wire:model ke properti answers_lainnya yang baru --}}
                                                            <input type="text"
                                                                wire:model="answers_lainnya.f1201_lainnya"
                                                                class="form-control"
                                                                placeholder="Ketik jawaban lainnya..." required>

                                                            {{-- Tampilkan error khusus untuk input lainnya --}}
                                                            @error('answers_lainnya.f1201_lainnya')
                                                                <small
                                                                    class="text-danger mt-2 d-block">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                    @if (
                                                        $question->question_code === 'f301' &&
                                                            isset($answers['f301']) &&
                                                            $answers['f301'] === $option->value &&
                                                            in_array($option->value, ['1', '2']))
                                                        <div class="ms-4 mb-3">
                                                            <!-- <label class="form-label small text-muted">Sebutkan lainnya:</label> -->
                                                            {{-- Gunakan wire:model ke properti answers_lainnya yang baru --}}
                                                            <input type="number"
                                                                wire:model="answers_lainnya.f301_lainnya"
                                                                class="form-control" placeholder="Ketik jawaban ..."
                                                                required>

                                                            {{-- Tampilkan error khusus untuk input lainnya --}}
                                                            @error('answers_lainnya.f301_lainnya')
                                                                <small
                                                                    class="text-danger mt-2 d-block">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                    @if (
                                                        $question->question_code === 'f1001' &&
                                                            isset($answers['f1001']) &&
                                                            $answers['f1001'] === $option->value &&
                                                            in_array($option->value, ['5']))
                                                        <div class="ms-4 mb-3">
                                                            <!-- <label class="form-label small text-muted">Sebutkan lainnya:</label> -->
                                                            {{-- Gunakan wire:model ke properti answers_lainnya yang baru --}}
                                                            <input type="number"
                                                                wire:model="answers_lainnya.f1001_lainnya"
                                                                class="form-control" placeholder="Ketik jawaban ..."
                                                                required>

                                                            {{-- Tampilkan error khusus untuk input lainnya --}}
                                                            @error('answers_lainnya.f1001_lainnya')
                                                                <small
                                                                    class="text-danger mt-2 d-block">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- 2. Number Input --}}
                                        @if ($question->input_type === 'number')
                                            <input type="number" wire:model="answers.{{ $question->question_code }}"
                                                class="form-control form-control-lg" placeholder="Masukkan angka..."
                                                min="0" required>
                                        @endif

                                        {{-- 3. Text Input --}}
                                        @if ($question->input_type === 'text')
                                            <input type="text" wire:model="answers.{{ $question->question_code }}"
                                                class="form-control form-control-lg" placeholder="Ketik jawaban Anda..."
                                                required>
                                        @endif

                                        {{-- 4. Select Dropdown (INPUT BARU) --}}
                                        @if ($question->input_type === 'select')
                                            <select wire:model.live="answers.{{ $question->question_code }}"
                                                class="form-select form-select-lg" id="{{ $question->question_code }}"
                                                {{-- Gunakan .live jika pilihan dropdown ini memicu pertanyaan kondisional berikutnya --}}
                                                {{ $question->conditional_parent_code ? 'wire:model.live' : 'wire:model' }}
                                                required>

                                                <option value="">-- Silakan Pilih --</option>

                                                @foreach ($question->options as $option)
                                                    <option value="{{ $option->value }}">{{ $option->label }}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                        {{-- 5. Date Input --}}
                                        @if ($question->input_type === 'date')
                                            <input type="date" wire:model="answers.{{ $question->question_code }}"
                                                class="form-control form-control-lg" placeholder="dd/mm/yy" required>
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
                                                            $gridRows =
                                                                $this->gridRowsGrouped[$question->id] ?? collect();
                                                        @endphp

                                                        @foreach ($gridRows as $gridRow)
                                                            <tr>


                                                                {{-- Kolom A (Saat Lulus) --}}
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <td class="text-center">
                                                                        <input type="radio" {{-- BINDING LIVEWIRE DENGAN KODE: QCode.RowCode_Column --}}
                                                                            wire:model="gridAnswers.{{ $question->question_code }}.{{ $gridRow->row_code }}_A"
                                                                            value="{{ $i }}"
                                                                            name="{{ $gridRow->row_code }}_A"
                                                                            required>
                                                                    </td>
                                                                @endfor

                                                                {{-- Teks Kompetensi --}}
                                                                <td class="text-center">{{ $gridRow->row_label }}</td>

                                                                {{-- Kolom B (Saat Ini di Pekerjaan) --}}
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <td class="text-center">
                                                                        <input type="radio" {{-- BINDING LIVEWIRE DENGAN KODE: QCode.RowCode_Column --}}
                                                                            wire:model="gridAnswers.{{ $question->question_code }}.{{ $gridRow->row_code }}_B"
                                                                            value="{{ $i }}"
                                                                            name="{{ $gridRow->row_code }}_B"
                                                                            required>
                                                                    </td>
                                                                @endfor
                                                            </tr>

                                                            {{-- Tampilkan error di bawah baris Grid --}}
                                                            @error("gridAnswers.{$question->question_code}.{$gridRow->row_code}_A")
                                                                <tr>
                                                                    <td colspan="11" class="text-danger small">
                                                                        {{ $message }} (Kolom A)</td>
                                                                </tr>
                                                            @enderror
                                                            @error("gridAnswers.{$question->question_code}.{$gridRow->row_code}_B")
                                                                <tr>
                                                                    <td colspan="11" class="text-danger small">
                                                                        {{ $message }} (Kolom B)</td>
                                                                </tr>
                                                            @enderror
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{-- ... Keterangan ... --}}
                                            </div>
                                        @endif

                                        {{-- 7. Checkbox (Jawaban Bisa Lebih dari Satu) --}}
                                        @if ($question->input_type === 'checkbox')
                                            <div class="ms-3">
                                                {{-- Tentukan kode pertanyaan saat ini --}}
                                                @php $currentCode = $question->question_code; @endphp

                                                @foreach ($question->options as $option)
                                                    <div class="form-check mb-2"
                                                        wire:key="option-{{ $currentCode }}-{{ $option->id }}">
                                                        <input class="form-check-input" type="checkbox"
                                                            {{-- 1. PERBAIKAN: Binding ke array yang sama --}}
                                                            wire:model="answers.{{ $currentCode }}_{{ $option->id }}"
                                                            value="{{ $option->value }}"
                                                            id="opsi_{{ $currentCode }}_{{ $option->id }}">

                                                        <label class="form-check-label"
                                                            for="opsi_{{ $currentCode }}_{{ $option->id }}">
                                                            {{ $option->label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- 8. Select2 Provinsi --}}
                                        @if ($question->input_type === 'select2' && $question->question_code === 'f5a1')
                                            <div x-data="{ questionCode: '{{ $question->question_code }}' }" x-init="let select = $('#provinsi-select');
                                            
                                            select.select2({
                                                placeholder: '-- Pilih Provinsi --',
                                                allowClear: true,
                                                width: '100%'
                                            });
                                            
                                            select.on('change', function(e) {
                                                let value = $(this).val();
                                                $wire.set('answers.' + questionCode, value);
                                            });
                                            
                                            $watch('$wire.answers.{{ $question->question_code }}', value => {
                                                if (select.val() != value) {
                                                    select.val(value).trigger('change.select2');
                                                }
                                            });" wire:ignore>
                                                <select class="form-select form-select-lg" id="provinsi-select">
                                                    <option value="">-- Pilih Provinsi --</option>
                                                    @foreach ($provinsi as $item)
                                                        <option value="{{ $item->kode_provinsi }}"
                                                            {{ isset($answers[$question->question_code]) && $answers[$question->question_code] == $item->kode_provinsi ? 'selected' : '' }}>
                                                            {{ $item->nama_provinsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @error("answers.{$question->question_code}")
                                                <small class="text-danger mt-2 d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </small>
                                            @enderror
                                        @endif

                                        {{-- 9. Select2 Kabupaten --}}
                                        @if ($question->input_type === 'select2' && $question->question_code === 'f5a2')
                                            <div x-data="{
                                                questionCode: '{{ $question->question_code }}',
                                                initSelect: false
                                            }" x-init="let select = $('#kabupaten-select');
                                            
                                            function initKabupaten() {
                                                // Destroy existing Select2 if exists
                                                if (select.hasClass('select2-hidden-accessible')) {
                                                    select.select2('destroy');
                                                }
                                            
                                                // Re-initialize Select2
                                                select.select2({
                                                    placeholder: '-- Pilih Kabupaten --',
                                                    allowClear: true,
                                                    width: '100%'
                                                });
                                            
                                                // Bind change event only once
                                                if (!initSelect) {
                                                    select.on('change', function(e) {
                                                        let value = $(this).val();
                                                        $wire.set('answers.' + questionCode, value);
                                                    });
                                                    initSelect = true;
                                                }
                                            }
                                            
                                            initKabupaten();
                                            
                                            // Listen to the kabupatenUpdated event and update options manually
                                            $wire.on('kabupatenUpdated', (kabupatenData) => {
                                                // Clear existing options except the placeholder
                                                select.find('option:not(:first)').remove();
                                                // Add new options
                                                kabupatenData.forEach(item => {
                                                    select.append(`<option value='${item.kode_kabupaten_kota}'>
                                                                                                                                                                                                                                                                                                                        ${item.nama_kabupaten_kota}</option>`);
                                                });
                                                // Re-init select2
                                                setTimeout(() => {
                                                    initKabupaten();
                                                }, 50);
                                            });
                                            
                                            $wire.on('$wire.answers.{{ $question->question_code }}', value => {
                                                if (select.val() != value) {
                                                    select.val(value).trigger('change.select2');
                                                }
                                            });" wire:ignore.self>
                                                <select class="form-select form-select-lg" id="kabupaten-select">
                                                    <option value="">-- Pilih Kabupaten --</option>
                                                    @if ($kabupaten && count($kabupaten) > 0)
                                                        @foreach ($kabupaten as $item)
                                                            <option value="{{ $item->kode_kabupaten }}"
                                                                {{ isset($answers[$question->question_code]) && $answers[$question->question_code] == $item->kode_kabupaten ? 'selected' : '' }}>
                                                                {{ $item->nama_kabupaten_kota }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            @error("answers.{$question->question_code}")
                                                <small class="text-danger mt-2 d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </small>
                                            @enderror
                                        @endif

                                        {{-- Tampilkan Error Validation --}}
                                        @error("answers.{$question->question_code}")
                                            <small class="text-danger mt-2 d-block"><i
                                                    class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                        @enderror

                                    </div>
                                @endif
                            @endforeach

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ url('/') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" disabled>
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Kuesioner
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
