<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Option;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\UserAnswer;
use App\Models\GridRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Pilih extends Component
{
    public $alumniData;
    public $alumniId;

    public $answers = [];
    public $answersLainnya = [];
    public $questions;
    public $provinsis;
    public $kabupatens = [];
    public $provinsi_code = null;
    public $currentCode;
    public $questionCodes;
    public $gridAnswers = [];
    public $dataDiri, $prodi;

    public function mount()
    {
        // Ambil data dari session
        $this->alumniData = Session::get('alumni_data');
        $this->alumniId = Session::get('alumni_id');
        // Ambil semua pertanyaan, urutkan berdasarkan ID
        $this->questions = Question::with('options')->orderBy('id')->get();
        $this->provinsis = Provinsi::orderBy('nama_provinsi')->get();
        // Inisialisasi kabupatens sebagai collection kosong
        // Tidak perlu query di sini karena provinsi_code masih null
        $this->kabupatens = collect([]);
        $this->questionCodes = $this->questions->pluck('question_code')->toArray();
    }
    public function getGridRowsGroupedProperty()
    {
        // Livewire TIDAK akan mencoba serialize properti computed ini.
        // Ini akan dipanggil setiap render.
        return GridRow::all()->groupBy('question_id');
    }
    public function updatedAnswers($value, $key)
    {
        if ($key == 'f8') {
            $this->answers = [];
            $this->gridAnswers = [];
            $this->answers['f8'] = $value;
            $this->answersLainnya = [];
        }
        if ($key == 'f5a1') {

            $this->provinsi_code = $value;

            // Load kabupaten berdasarkan provinsi yang dipilih
            $this->kabupatens = Kabupaten::where('kode_provinsi', $this->provinsi_code)->orderBy('nama_kabupaten_kota')->get();

            // Reset jawaban kabupaten jika ada
            if (isset($this->answers['f5a2'])) {
                $this->answers['f5a2'] = null;
            }
        }
        if ($key == 'f301') {
            $this->resetErrorBag("answersLainnya.{$key}");
            $this->answersLainnya['f301'] = '';
        }
    }
    // Hook ketika custom input berubah
    public function updatedAnswersLainnya($value, $key)
    {
        // Hapus error saat user mulai mengetik
        $this->resetErrorBag("answersLainnya.{$key}");

        // Opsional: Validasi realtime
        if ($key !== 'f301') {

            if (!empty($value)) {
                if (strlen($value) >= 3) {
                    $this->resetErrorBag("answersLainnya.{$key}");
                } else {
                    $this->addError("answersLainnya.{$key}", 'Jawaban minimal 3 karakter.');
                }
            }
        }
    }
    /**
     * Tentukan apakah sebuah pertanyaan harus ditampilkan berdasarkan jawaban sebelumnya.
     */
    public function shouldShow(Question $question)
    {
        // 1. Jika pertanyaan tidak memiliki kondisi (parent_code NULL), tampilkan.
        if (is_null($question->conditional_parent_code)) {
            return true;
        }

        $parentCode = $question->conditional_parent_code;
        $parentValue = $question->conditional_parent_value;

        // 2. Cek apakah parentCode sudah dijawab
        if (array_key_exists($parentCode, $this->answers)) {
            // 3. Cek apakah jawaban parent cocok dengan conditional_parent_value
            return (string)$this->answers[$parentCode] === (string)$parentValue;
        }

        return false;
    }
    public function submitForm()
    {
        // dd($this->answers, $this->answersLainnya);
        $rules = [];
        $messages = [];

        $tipepertamas = Question::whereIn('input_type', ['select', 'radio', 'number', 'text'])->get();
        foreach ($tipepertamas as $question) {
            $code = $question->question_code;
            // Validasi jawaban utama (wajib untuk semua pertanyaan)
            if ($this->shouldShow($question)) {
                $rules["answers.{$question->question_code}"] = 'required';
                $messages["answers.{$question->question_code}.required"] = "Jawaban untuk pertanyaan ini wajib diisi.";
            } else {
                $rules["answers.{$code}"] = 'nullable';
            }


            // Cek apakah ada opsi dengan custom input
            $hasCustomOption = $question->options->contains('is_custom_input', true);

            if ($hasCustomOption) {
                // Ambil nilai yang dipilih user
                $selectedValue = $this->answers[$question->question_code] ?? null;

                if ($selectedValue) {
                    // Cek apakah opsi yang dipilih adalah opsi dengan custom input
                    $selectedOption = $question->options->firstWhere('id', $selectedValue);

                    // Jika opsi yang dipilih memiliki custom input, maka wajib diisi
                    if ($selectedOption && $selectedOption->is_custom_input) {
                        if ($question->question_code == 'f301') {
                            $rules["answersLainnya.{$question->question_code}"] = 'required|string|max:255';
                        } else {
                            $rules["answersLainnya.{$question->question_code}"] = 'required|string|min:3|max:255';
                        }
                        $messages["answersLainnya.{$question->question_code}.required"] = "Mohon isi jawaban lainnya untuk pertanyaan ini.";
                        $messages["answersLainnya.{$question->question_code}.min"] = "Jawaban minimal 3 karakter.";
                        $messages["answersLainnya.{$question->question_code}.max"] = "Jawaban maksimal 255 karakter.";
                    }
                }
            }
        }
        $gridQuestions = Question::where('input_type', 'grid')->get();
        foreach ($gridQuestions as $question) {
            $gridRows = $this->gridRowsGrouped[$question->id] ?? collect();

            foreach ($gridRows as $gridRow) {
                // Validasi untuk kolom A (Saat Lulus)
                $keyA = "{$question->question_code}.{$gridRow->row_code}_A";
                $rules["gridAnswers.{$keyA}"] = 'required|integer|between:1,5';
                $messages["gridAnswers.{$keyA}.required"] = "Kompetensi '{$gridRow->row_label}' kolom A (Saat Lulus) wajib diisi";

                // Validasi untuk kolom B (Saat Ini di Pekerjaan)
                $keyB = "{$question->question_code}.{$gridRow->row_code}_B";
                $rules["gridAnswers.{$keyB}"] = 'required|integer|between:1,5';
                $messages["gridAnswers.{$keyB}.required"] = "Kompetensi '{$gridRow->row_label}' kolom B (Saat Ini) wajib diisi";
            }
        }
        $checkBoxs = Question::where('input_type', 'checkbox')->get();
        foreach ($checkBoxs as $question) {
            // Filter jawaban untuk pertanyaan ini
            $question_code = $question->question_code;

            // 2. Tambahkan Closure Rule ke array $rules
            $hasAnswerTrue = collect($this->answers)
                ->filter(fn($value, $key) => str_starts_with($key, $question_code . '_') && $value === true)
                ->isNotEmpty();
            if (!$hasAnswerTrue) {
                // $this->addError("answers_" . $question_code, 'Anda harus memilih minimal satu jawaban untuk .');
                $rules["answers.{$question_code}"] = [
                    'required',
                ];
                $messages["answers.{$question_code}.required"] = 'Anda harus memilih minimal satu jawaban untuk pertanyaan ini';
            }


            // Validasi custom inputs
            $customOptions = Option::where('question_id', $question->id)
                ->where('is_custom_input', true)
                ->get();

            foreach ($customOptions as $customOption) {
                $customKey = "{$question_code}_{$customOption->id}";
                $answerKey = "{$question_code}_{$customOption->id}";

                // Cek apakah checkbox "Lainnya" dipilih
                if (isset($this->answers[$answerKey]) && $this->answers[$answerKey]) {
                    // Rule untuk custom input
                    $rules["answersLainnya.{$customKey}"] = [
                        'required',
                        'min:3',
                        'max:255'
                    ];

                    // Messages untuk custom input
                    $messages["answersLainnya.{$customKey}.required"] = 'Mohon isi jawaban untuk opsi "Lainnya".';
                    $messages["answersLainnya.{$customKey}.min"] = 'Jawaban minimal 3 karakter.';
                    $messages["answersLainnya.{$customKey}.max"] = 'Jawaban maksimal 255 karakter.';
                }
            }
        }

        // Validasi
        $this->validate($rules, $messages);

        // Jika validasi berhasil, proses data
        $this->saveData();
    }

    private function saveData()
    {
        try {
            DB::beginTransaction();
            // Ambil semua question_id dengan tipe tertentu
            $alumnusId = $this->alumniId;
            $tahunPelaksana = date('Y');
            $questionIds = Question::whereIn('input_type', ['select', 'radio', 'number', 'text'])
                ->pluck('id')
                ->toArray();
            // Hapus semua jawaban lama untuk pertanyaan-pertanyaan tersebut
            UserAnswer::where('alumnus_id', $alumnusId)
                ->where('tahun_pelaksana', $tahunPelaksana)
                ->whereIn('question_id', $questionIds)
                ->delete();
            $tipepertamas = Question::whereIn('input_type', ['select', 'radio', 'number', 'text'])->get();

            foreach ($tipepertamas as $question) {
                $questionCode = $question->question_code;
                $answerId = $this->answers[$questionCode] ?? null;

                if ($answerId) {

                    if (in_array($question->input_type, ['number', 'text', 'date'])) {
                        $answerValue = $answerId;
                        $answerId = null;
                    } else {
                        $selectedOption = $question->options->firstWhere('id', $answerId);
                        if (in_array($questionCode, ['f5a1', 'f5a2'])) {
                            $answerValue = $answerId;
                            $answerId = null;
                        } else {
                            $answerValue = $answerId;
                            if ($selectedOption) {
                                // Jika ada custom input, gunakan nilai custom
                                if ($selectedOption->is_custom_input && isset($this->answersLainnya[$questionCode])) {

                                    $answerValue = $this->answersLainnya[$questionCode];
                                } else {

                                    $answerValue = $selectedOption->value;
                                }
                            }
                        }
                    }

                    // Update jika ada, Create jika belum ada
                    UserAnswer::updateOrCreate(
                        [
                            // Kriteria pencarian (unique identifier)
                            'alumnus_id' => $alumnusId,
                            'tahun_pelaksana' => $tahunPelaksana,
                            'question_id' => $question->id,
                        ],
                        [
                            // Data yang akan di-update atau di-create
                            'question_option_id' => $answerId,
                            'answer_value' => $answerValue,
                            'question_code' => $questionCode,
                            'grid_row_code' => null,
                            'grid_column' => null,
                        ]
                    );
                }
            }
            // 3. Loop dan simpan setiap jawaban grid
            foreach ($this->gridAnswers as $questionCode => $rows) {

                // Ambil question berdasarkan question_code
                $question = Question::where('question_code', $questionCode)->first();

                if (!$question) {
                    continue;
                }

                foreach ($rows as $rowKey => $value) {
                    // Parse row_code dan column (A atau B)
                    // Format: row_code_A atau row_code_B
                    $parts = explode('_', $rowKey);
                    $column = array_pop($parts); // A atau B
                    $rowCode = implode('_', $parts);

                    // Ambil grid row
                    $gridRow = GridRow::where('row_code', $rowCode)
                        ->where('question_id', $question->id)
                        ->first();

                    if (!$gridRow) {
                        continue;
                    }

                    UserAnswer::updateOrCreate(
                        [
                            // Kriteria pencarian (unique identifier)
                            'alumnus_id' => $alumnusId,
                            'tahun_pelaksana' => $tahunPelaksana,
                            'question_id' => $question->id,
                            'grid_row_code' => $rowCode,
                            'grid_column' => $column,
                        ],
                        [
                            'question_option_id' => null,
                            'answer_value' => $value,
                            'question_code' => $question->question_code,
                            // 'grid_row_code' => $rowCode,
                            // 'grid_column' => $column,
                        ]
                    );
                }
            }
            // 3. Loop dan simpan setiap jawaban checkbox
            $checkBoxs = Question::where('input_type', 'checkbox')->get();
            foreach ($checkBoxs as $question) {
                $question_code = $question->question_code;
                // Ambil selected answers untuk pertanyaan ini
                $selectedAnswers = collect($this->answers)
                    ->filter(function ($value, $key) use ($question_code) {
                        return $value == true && strpos($key, $question_code . '_') === 0;
                    })
                    ->toArray();
                // 1. Hapus dulu semua jawaban lama untuk question ini
                UserAnswer::where('alumnus_id', $alumnusId)
                    ->where('tahun_pelaksana', $tahunPelaksana)
                    ->where('question_id', $question->id)
                    ->delete();
                foreach ($selectedAnswers as $key => $value) {
                    if ($value) {
                        $parts = explode('_', $key);
                        $optionId = end($parts);

                        $option = Option::find($optionId);

                        if ($option) {
                            $customValue = null;

                            if ($option->is_custom_input) {
                                $customValue = $this->answersLainnya[$key] ?? null;
                            }

                            UserAnswer::updateOrCreate(
                                [
                                    // Kriteria pencarian (unique identifier)
                                    'alumnus_id' => $alumnusId,
                                    'tahun_pelaksana' => $tahunPelaksana,
                                    'question_id' => $question->id,
                                    'question_option_id' => $optionId,
                                ],
                                [
                                    // 'question_option_id' => $optionId,
                                    'answer_value' => $customValue,
                                    'question_code' => $question->question_code,
                                    'grid_row_code' => null,
                                    'grid_column' => null,
                                ]
                            );
                        }
                    }
                }
            }
            DB::commit();
            session()->flash('success_message', 'Semua jawaban berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving answers: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan jawaban.');
        }
    }

    public function render()
    {
        return view('livewire.pilih')->layout('layouts.master');
    }
}
