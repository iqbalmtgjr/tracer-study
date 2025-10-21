<?php

namespace App\Livewire;

use App\Models\GridRow;
use Livewire\Component;
use App\Models\Provinsi;
use App\Models\Question;
use App\Models\Kabupaten;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;

class QuizForm extends Component
{
    public $provinsi;
    public $kabupaten = [];
    // Properti untuk menyimpan ID alumni yang sedang mengisi
    public $alumnusId = 1; // Ganti dengan Auth::id() atau ID alumni yang sesuai

    // Array untuk menyimpan jawaban: ['f8' => 1, 'f502_bekerja' => 5, ...]
    public $answers = [];

    // Properti baru untuk menyimpan input teks tambahan dari opsi 'Lainnya'
    public $answers_lainnya = [];

    // Properti untuk menyimpan daftar semua pertanyaan dari database
    public $questions;

    // Properti baru untuk menyimpan jawaban Grid: 
    // Contoh: ['Q13' => ['f1761_A' => 4, 'f1761_B' => 5, ...]]
    public $gridAnswers = [];

    public function getGridRowsGroupedProperty()
    {
        // Livewire TIDAK akan mencoba serialize properti computed ini.
        // Ini akan dipanggil setiap render.
        return \App\Models\GridRow::all()->groupBy('question_id');
    }
    public function mount()
    {
        // Ambil semua pertanyaan, urutkan berdasarkan ID
        $this->questions = Question::with('options')->orderBy('id')->get();

        // ✅ Inisialisasi provinsi sebagai collection kosong
        $this->provinsi = Provinsi::all();
        // ✅ Inisialisasi kabupaten sebagai collection kosong
        $this->kabupaten = collect([]);

        // ✅ OPTIONAL: Load kabupaten jika ada provinsi yang sudah dipilih sebelumnya
        if (isset($this->answers['f5a1']) && !empty($this->answers['f5a1'])) {
            $this->loadKabupaten($this->answers['f5a1']);
        }
    }

    public function loadKabupaten($kodeProvinsi)
    {
        if ($kodeProvinsi) {
            $this->kabupaten = Kabupaten::where('kode_provinsi', $kodeProvinsi)
                ->orderBy('nama_kabupaten_kota')
                ->get();

            // ✅ Dispatch event ke frontend dengan data kabupaten
            $this->dispatch('kabupatenUpdated', $this->kabupaten->toArray());

            // Reset pilihan kabupaten jika provinsi berubah
            if (isset($this->answers['f5a2'])) {
                unset($this->answers['f5a2']);
            }
        } else {
            $this->kabupaten = collect([]);
            $this->dispatch('kabupatenUpdated', []);
        }
    }

    // Metode ini dipanggil secara otomatis oleh Livewire saat ada perubahan input.
    // Kita gunakan ini untuk memastikan pertanyaan kondisional muncul/hilang.
    public function updated($field)
    {
        // Deteksi jika yang diupdate adalah provinsi (f5a1)
        if ($field === 'answers.f5a1') {
            $this->loadKabupaten($this->answers['f5a1']);

            // ✅ Force refresh untuk memastikan view di-update
            $this->dispatch('$refresh');
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
        $rules = $this->getValidationRules();
        $this->validate($rules);

        // Dapatkan pemetaan Question Code ke ID untuk akses cepat
        $questionMap = $this->questions->pluck('id', 'question_code');

        DB::transaction(function () use ($questionMap) {

            // 1. Simpan Jawaban Pertanyaan Tunggal (Non-Grid)
            foreach ($this->answers as $code => $value) {
                $questionId = $questionMap->get($code);

                if ($questionId && !empty($value)) {
                    UserAnswer::updateOrCreate(
                        [
                            'alumnus_id' => $this->alumnusId,
                            'question_code' => $code,
                            // Pastikan kolom grid_row_code dan grid_column diset NULL untuk non-grid
                            'grid_row_code' => null,
                            'grid_column' => null,
                        ],
                        [
                            'question_id' => $questionId,
                            'answer_value' => is_array($value) ? json_encode($value) : $value,
                        ]
                    );
                }
            }

            // 2. Simpan Jawaban Teks Tambahan (answers_lainnya)
            foreach ($this->answers_lainnya as $codeKey => $value) {
                // Contoh: $codeKey = 'f1101_lainnya'
                $baseCode = str_replace('_lainnya', '', $codeKey); // Hasil: 'f1101'
                $questionId = $questionMap->get($baseCode);

                if ($questionId && !empty($value)) {
                    UserAnswer::updateOrCreate(
                        [
                            'alumnus_id' => $this->alumnusId,
                            'question_code' => $codeKey, // Gunakan kode unik f1101_lainnya
                        ],
                        [
                            'question_id' => $questionId,
                            'answer_value' => $value,
                            'grid_row_code' => null,
                            'grid_column' => null,
                        ]
                    );
                }
            }

            // 3. Simpan Jawaban Grid
            foreach ($this->gridAnswers as $qCode => $answers) {
                $questionId = $questionMap->get($qCode);

                if (!$questionId) continue;

                foreach ($answers as $rowKey => $value) {
                    // $rowKey = f1761_A
                    $parts = explode('_', $rowKey);
                    $rowCode = $parts[0]; // f1761
                    $column = $parts[1];  // A atau B

                    UserAnswer::updateOrCreate(
                        [
                            'alumnus_id' => $this->alumnusId,
                            'question_code' => $qCode,
                            'grid_row_code' => $rowCode, // Kunci unik
                            'grid_column' => $column,     // Kunci unik
                        ],
                        [
                            'question_id' => $questionId,
                            'answer_value' => $value,
                        ]
                    );
                }
            }
        });

        session()->flash('success_message', 'Kuesioner berhasil disimpan!');
        // $this->redirect('halaman-berikutnya');
    }
    protected function getValidationRules()
    {
        $rules = [];

        // Daftar semua kode pertanyaan yang memicu input 'Lainnya' dan nilai pemicunya
        $otherInputTriggers = [
            'f1101' => '5', // f1101 (Jenis Perusahaan) memicu input teks jika nilai = 5 ('Lainnya')
            // Tambahkan trigger lain di sini, cth: 'f1201' => '7',
        ];

        // --- LOOP UTAMA ---
        foreach ($this->questions as $question) {
            $code = $question->question_code;

            // 1. VALIDASI PERTANYAAN TUNGGAL (RADIO/TEXT/NUMBER/SELECT)
            if ($this->shouldShow($question)) {
                $rules["answers.{$code}"] = 'required';
            } else {
                $rules["answers.{$code}"] = 'nullable';
            }

            // 2. VALIDASI GRID/MATRIX
            if ($question->input_type === 'grid' && $this->shouldShow($question)) {
                // Kita sudah memuat gridRows di mount() dan mengelompokkannya berdasarkan question_id
                if (isset($this->gridRows[$question->id])) {
                    foreach ($this->gridRows[$question->id] as $row) {
                        $rowCode = $row->row_code;

                        // Validasi Kolom A dan Kolom B untuk setiap baris Grid
                        $rules["gridAnswers.{$code}.{$rowCode}_A"] = 'required|integer|min:1|max:5';
                        $rules["gridAnswers.{$code}.{$rowCode}_B"] = 'required|integer|min:1|max:5';
                    }
                }
            }
        }

        // --- VALIDASI INPUT "LAINNYA" KHUSUS (f1101, f1201, dll.) ---
        foreach ($otherInputTriggers as $questionCode => $triggerValue) {
            $inputKey = "{$questionCode}_lainnya"; // Contoh: f1101_lainnya

            if (isset($this->answers[$questionCode]) && $this->answers[$questionCode] === $triggerValue) {
                // Wajib diisi jika opsi pemicu terpilih
                $rules["answers_lainnya.{$inputKey}"] = 'required|string|max:255';
            } else {
                // Diabaikan jika opsi pemicu tidak terpilih
                $rules["answers_lainnya.{$inputKey}"] = 'nullable';

                // Opsional: Hapus nilai dari properti answers_lainnya
                // agar data bersih jika pengguna berganti pilihan
                unset($this->answers_lainnya[$inputKey]);
            }
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.quiz-form')->layout('layouts.master');
    }
}
