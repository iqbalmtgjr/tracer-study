<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Option;
use App\Models\UserAnswer;
use App\Models\GridRow;

use Illuminate\Support\Facades\DB;

class Grid extends Component
{

    // Array untuk menyimpan jawaban: ['f8' => 1, 'f502_bekerja' => 5, ...]
    public $answers = [];

    // Properti baru untuk menyimpan input teks tambahan dari opsi 'Lainnya'
    public $answersLainnya = [];

    // Properti untuk menyimpan daftar semua pertanyaan dari database
    public $questions;
    public $currentCode; // Kode pertanyaan saat ini
    public $questionCodes;

    public $gridAnswers = [];
    // public $gridRowsGrouped = [];

    public function mount()
    {
        // Ambil semua pertanyaan, urutkan berdasarkan ID
        $this->questions = Question::with('options')->where('input_type', 'grid')->orderBy('id')->get();
        $this->questionCodes = $this->questions->pluck('question_code')->toArray();
    }

    public function getGridRowsGroupedProperty()
    {
        // Livewire TIDAK akan mencoba serialize properti computed ini.
        // Ini akan dipanggil setiap render.
        return GridRow::all()->groupBy('question_id');
    }
    // Method untuk validasi grid answers
    protected function validateGridAnswers()
    {
        $rules = [];
        $messages = [];

        // Loop setiap question yang bertipe grid
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

        $this->validate($rules, $messages);
    }
    public function submitForm()
    {
        try {
            DB::beginTransaction();

            // 1. Validasi grid answers
            $this->validateGridAnswers();

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

                    $datas = [
                        'alumnus_id' => 2,
                        'question_id' => $question->id,
                        'question_option_id' => null,
                        'answer_value' => $value,
                        'question_code' => $question->question_code,
                        'grid_row_code' => $rowCode,
                        'grid_column' => $column,
                    ];
                    UserAnswer::create($datas);
                }
            }

            DB::commit();

            // Reset form jika diperlukan
            // $this->reset('gridAnswers');

            // Flash message sukses
            session()->flash('success_message', 'Data berhasil disimpan!');

            // Redirect jika diperlukan
            // return redirect()->route('survey.thanks');

            // Atau emit event
            // $this->emit('formSubmitted');

            // Atau refresh data
            // $this->emit('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            // Error validasi akan otomatis ditampilkan
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            \Log::error('Error saving grid answers: ' . $e->getMessage());

            // Flash error message
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');

            // Atau tampilkan pesan ke user
            $this->addError('submit', 'Terjadi kesalahan: ' . $e->getMessage());
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
    // protected function saveQuestionAnswers($question, $questionCode)
    // {

    //     // Ambil selected answers untuk pertanyaan ini
    //     $selectedAnswers = collect($this->answers)
    //         ->filter(function ($value, $key) use ($questionCode) {
    //             return $value == true && strpos($key, $questionCode . '_') === 0;
    //         })
    //         ->toArray();

    //     // Simpan detail
    //     foreach ($selectedAnswers as $key => $value) {
    //         if ($value) {
    //             $parts = explode('_', $key);
    //             $optionId = end($parts);

    //             $option = Option::find($optionId);

    //             if ($option) {
    //                 $customValue = null;

    //                 if ($option->is_custom_input) {
    //                     $customValue = $this->answersLainnya[$key] ?? null;
    //                 }
    //                 $datas = [
    //                     'alumnus_id' => 2,
    //                     'question_id' => $question->id,
    //                     'question_option_id' => $optionId,
    //                     'answer_value' => $customValue,
    //                     'question_code' => $question->question_code,
    //                     'grid_row_code' => null,
    //                     'grid_column' => null,
    //                 ];
    //                 UserAnswer::create($datas);
    //             }
    //         }
    //     }
    // }
    // protected function rules()
    // {
    //     $rules = [];

    //     // Loop melalui setiap question yang memiliki grid rows
    //     foreach ($this->gridRowsGrouped as $questionCode => $gridRows) {
    //         foreach ($gridRows as $gridRow) {
    //             $rowCode = $gridRow->row_code;

    //             // Validasi untuk kolom A (Saat Lulus)
    //             $rules["gridAnswers.{$questionCode}.{$rowCode}_A"] = [
    //                 'required',
    //                 'integer',
    //                 'between:1,5'
    //             ];

    //             // Validasi untuk kolom B (Saat Ini di Pekerjaan)
    //             $rules["gridAnswers.{$questionCode}.{$rowCode}_B"] = [
    //                 'required',
    //                 'integer',
    //                 'between:1,5'
    //             ];
    //         }
    //     }

    //     return $rules;
    // }
    // Custom error messages
    // protected function messages()
    // {
    //     $messages = [];

    //     foreach ($this->gridRowsGrouped as $questionCode => $gridRows) {
    //         foreach ($gridRows as $gridRow) {
    //             $rowCode = $gridRow->row_code;
    //             $rowLabel = $gridRow->row_label;

    //             // Pesan untuk kolom A
    //             $messages["gridAnswers.{$questionCode}.{$rowCode}_A.required"] =
    //                 "Kolom A untuk '{$rowLabel}' wajib diisi";
    //             $messages["gridAnswers.{$questionCode}.{$rowCode}_A.between"] =
    //                 "Nilai kolom A untuk '{$rowLabel}' harus antara 1-5";

    //             // Pesan untuk kolom B
    //             $messages["gridAnswers.{$questionCode}.{$rowCode}_B.required"] =
    //                 "Kolom B untuk '{$rowLabel}' wajib diisi";
    //             $messages["gridAnswers.{$questionCode}.{$rowCode}_B.between"] =
    //                 "Nilai kolom B untuk '{$rowLabel}' harus antara 1-5";
    //         }
    //     }

    //     return $messages;
    // }

    public function render()
    {

        return view('livewire.grid')->layout('layouts.master');
    }
}
