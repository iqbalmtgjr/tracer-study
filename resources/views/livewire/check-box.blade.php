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

                            {{-- Inkremen penomorannya di sini, HANYA jika pertanyaan ditampilkan --}}
                            @php
                            $questionNumber++;
                            @endphp

                            <div class="mb-5 pb-4 border-bottom" wire:key="{{$question->question_code}}">
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
                                $errorKeyanswers = "answers_{$currentCode}";
                                @endphp
                                @error($errorKeyanswers)
                                <small class="text-danger mt-2 d-block"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                @enderror

                            </div>

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
                </div>
            </div>
        </div>
    </div>
    @push('header')

    @endpush
    @push('footer')

    @endpush
    @push('scripts')

    <script>

    </script>
    @endpush
</div>