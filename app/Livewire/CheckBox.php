<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Option;
use App\Models\UserAnswer;

use Illuminate\Support\Facades\DB;

class CheckBox extends Component
{

    // Array untuk menyimpan jawaban: ['f8' => 1, 'f502_bekerja' => 5, ...]
    public $answers = [];

    // Properti baru untuk menyimpan input teks tambahan dari opsi 'Lainnya'
    public $answersLainnya = [];

    // Properti untuk menyimpan daftar semua pertanyaan dari database
    public $questions;
    public $currentCode; // Kode pertanyaan saat ini
    public $questionCodes;
    public function mount()
    {
        // Ambil semua pertanyaan, urutkan berdasarkan ID
        $this->questions = Question::with('options')->where('input_type', 'checkbox')->orderBy('id')->get();
        $this->questionCodes = $this->questions->pluck('question_code')->toArray();
    }

    // Hook ketika property answers berubah
    // public function updatedAnswers($value, $key)
    // {

    //     // Extract question code dari key (Q1_4 → Q1)
    //     $questionCode = explode('_', $key)[0];

    //     // Cek apakah ada checkbox terpilih HANYA untuk pertanyaan ini
    //     $hasSelectedAnswer = collect($this->answers)
    //         ->filter(function ($val, $answerKey) use ($questionCode) {
    //             // Filter: value=true DAN milik pertanyaan yang sama (Q1_*)
    //             return $val == true && str_starts_with($answerKey, $questionCode . '_');
    //         })
    //         ->isNotEmpty();
    //     // Reset error HANYA untuk pertanyaan ini (answers_Q1, bukan semua)
    //     if ($hasSelectedAnswer) {
    //         $this->resetErrorBag("answers_{$key}");
    //     }

    //     // Jika checkbox "Lainnya" di-uncheck, hapus error custom input
    //     if ($value == false) {
    //         $this->resetErrorBag("answersLainnya.{$key}");
    //     }
    // }
    // Hook ketika custom input berubah
    public function updatedAnswersLainnya($value, $key)
    {
        // Hapus error saat user mulai mengetik
        $this->resetErrorBag("answersLainnya.{$key}");

        // Opsional: Validasi realtime
        if (!empty($value)) {
            if (strlen($value) >= 3) {
                $this->resetErrorBag("answersLainnya.{$key}");
            } else {
                $this->addError("answersLainnya.{$key}", 'Jawaban minimal 3 karakter.');
            }
        }
    }

    public function submitForm()
    {
        $rules = [];
        $messages = [];
        // dd($this->answers);
        // dd($this->answers, $this->answersLainnya);
        try {
            DB::beginTransaction();

            // $hasError = false;
            // Loop setiap question code
            $hasAnswerf401True = collect($this->answers)
                ->filter(fn($value, $key) => str_starts_with($key, 'f401_') && $value === true)
                ->isNotEmpty();

            if (!$hasAnswerf401True) {
                $this->addError("answers_f401", 'Anda harus memilih minimal satu jawaban untuk .');
            }
            $hasAnswerf1601True = collect($this->answers)
                ->filter(fn($value, $key) => str_starts_with($key, 'f1601_') && $value === true)
                ->isNotEmpty();

            if (!$hasAnswerf1601True) {
                $this->addError("answers_f1601", 'Anda harus memilih minimal satu jawaban untuk .');
            }
            // dd($messages);
            DB::commit();
            session()->flash('message', 'Semua jawaban berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving answers: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan jawaban.');
        }
    }

    /**
     * Validasi satu pertanyaan
     */
    // protected function validateQuestion($question, $questionCode)
    // {
    //     // Filter jawaban untuk pertanyaan ini
    //     $selectedAnswers = collect($this->answers)
    //         ->filter(function ($value, $key) use ($questionCode) {
    //             return $value == true && strpos($key, $questionCode . '_') === 0;
    //         })
    //         ->toArray();
    //     // Validasi: Minimal satu checkbox
    //     if (empty($selectedAnswers)) {
    //         $this->addError("answers_{$questionCode}", 'Anda harus memilih minimal satu jawaban.');
    //         return false;
    //     }

    //     // Validasi custom inputs
    //     $customOptions = Option::where('question_id', $question->id)
    //         ->where('is_custom_input', true)
    //         ->get();

    //     foreach ($customOptions as $customOption) {
    //         $customKey = "{$questionCode}_{$customOption->id}";
    //         $answerKey = "{$questionCode}_{$customOption->id}";

    //         if (isset($this->answers[$answerKey]) && $this->answers[$answerKey]) {
    //             $customValue = $this->answersLainnya[$customKey] ?? '';

    //             if (empty($customValue)) {
    //                 $this->addError("answersLainnya.{$customKey}", 'Mohon isi jawaban untuk opsi "Lainnya".');
    //                 return false;
    //             }

    //             if (strlen($customValue) < 3) {
    //                 $this->addError("answersLainnya.{$customKey}", 'Jawaban minimal 3 karakter.');
    //                 return false;
    //             }

    //             if (strlen($customValue) > 255) {
    //                 $this->addError("answersLainnya.{$customKey}", 'Jawaban maksimal 255 karakter.');
    //                 return false;
    //             }
    //         }
    //     }

    //     return true;
    // }

    /**
     * Simpan jawaban untuk satu pertanyaan
     */
    protected function saveQuestionAnswers($question, $questionCode)
    {

        // Ambil selected answers untuk pertanyaan ini
        $selectedAnswers = collect($this->answers)
            ->filter(function ($value, $key) use ($questionCode) {
                return $value == true && strpos($key, $questionCode . '_') === 0;
            })
            ->toArray();

        // Simpan detail
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
                    $datas = [
                        'alumnus_id' => 2,
                        'question_id' => $question->id,
                        'question_option_id' => $optionId,
                        'answer_value' => $customValue,
                        'question_code' => $question->question_code,
                        'grid_row_code' => null,
                        'grid_column' => null,
                    ];
                    UserAnswer::create($datas);
                }
            }
        }
    }


    public function render()
    {

        return view('livewire.check-box')->layout('layouts.master');
    }
}
