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
                            <label class="form-label fw-bold fs-5 mb-3">
                                {{-- Gunakan $questionNumber --}}
                                <span class="badge bg-primary me-2">{{ $questionNumber }}</span>

                                {{ $question->text }}
                                {{-- Simbol wajib diisi tetap ada --}}
                                <span class="text-danger">*</span>
                            </label>
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
    <style>
        /* Styling untuk radio button dengan error */
        .border-danger {
            border-color: #dc3545 !important;
            border-width: 2px !important;
        }

        /* Animasi untuk menarik perhatian ke error */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .table-danger {
            animation: shake 0.5s ease-in-out;
        }

        /* Hover effect untuk radio button */
        input[type="radio"].border-danger:hover {
            box-shadow: 0 0 0 0.3rem rgba(220, 53, 69, 0.4) !important;
        }

        /* Background merah muda untuk cell yang error */
        .bg-danger.bg-opacity-10 {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }
    </style>
    @push('header')

    @endpush
    @push('footer')

    @endpush
    @push('scripts')

    <script>

    </script>
    @endpush
</div>