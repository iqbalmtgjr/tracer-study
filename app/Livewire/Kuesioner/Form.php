<?php

namespace App\Livewire\Kuesioner;

use Livewire\Component;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;

class Form extends Component
{
    public $kuesioner;
    public $pertanyaan;
    public $jawaban = [];
    public $currentStep = 1;
    public $totalSteps;

    public function mount()
    {
        // Ambil kuesioner aktif
        $this->kuesioner = Kuesioner::active()->with(['pertanyaan.opsiJawaban', 'pertanyaan.pertanyaanGrid'])->first();

        if (!$this->kuesioner) {
            session()->flash('error', 'Tidak ada kuesioner aktif saat ini.');
            return redirect('/');
        }

        $this->pertanyaan = $this->kuesioner->pertanyaan;
        $this->totalSteps = $this->pertanyaan->count();
    }

    public function nextStep()
    {
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        // Proses penyimpanan jawaban
        dd($this->jawaban);
    }

    public function shouldShowQuestion($pertanyaan)
    {
        if (!$pertanyaan->kondisi_tampil) {
            return true;
        }

        foreach ($pertanyaan->kondisi_tampil as $kode => $nilaiArray) {
            $jawaban = $this->jawaban[$kode] ?? null;

            if (!in_array($jawaban, $nilaiArray)) {
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        return view('livewire.kuesioner.form')->layout('layouts.master');
    }
}
