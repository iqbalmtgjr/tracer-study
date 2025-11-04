<?php

namespace App\Livewire\Kuesioner;

use Livewire\Component;
use App\Models\Alumni;
use Illuminate\Support\Facades\Session;

class Form extends Component
{
    public $nik, $nim, $tanggal_lahir;

    protected $rules = [
        'nik' => 'required|numeric|digits:16',
        'nim' => 'required|numeric',
        'tanggal_lahir' => 'required|date',
    ];

    protected $messages = [
        'nik.required' => 'NIK wajib diisi',
        'nik.numeric' => 'NIK harus berupa angka',
        'nik.digits' => 'NIK harus 16 digit',
        'nim.required' => 'NIM wajib diisi',
        'nim.numeric' => 'NIM harus berupa angka',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
        'tanggal_lahir.date' => 'Format tanggal tidak valid',
    ];

    public function render()
    {
        return view('livewire.kuesioner.form')->layout('layouts.master');
    }

    public function mount()
    {
        // Clear session jika ada
        Session::forget('tracer_verified');
    }

    public function submit()
    {
        // dd($this->nik, $this->nim, $this->tanggal_lahir);
        // Validasi input
        $this->validate();

        // Cek data di database (sesuaikan dengan nama tabel dan field Anda)
        $alumni = Alumni::where('nik', $this->nik)
            ->where('nim', $this->nim)
            ->whereDate('tanggal_lahir', $this->tanggal_lahir)
            ->first();

        if ($alumni) {
            // Simpan data ke session
            Session::put('tracer_verified', true);
            Session::put('alumni_id', $alumni->id);
            Session::put('alumni_data', [
                'nik' => $alumni->nik,
                'nim' => $alumni->nim,
                'nama' => $alumni->nama_lengkap,
                'nama_prodi' => $alumni->programstudi->program_studi,
                'tahun_lulus' => $alumni->tahun_lulus,
            ]);

            //Redirect ke halaman kuesioner
            return redirect()->route('tracer.form');
        } else {
            // Jika data tidak cocok
            session()->flash('error', 'Data NIK, NIM, dan Tanggal Lahir tidak cocok. Silakan periksa kembali.');

            // // Reset form
            $this->reset(['nik', 'nim', 'tanggal_lahir']);
        }
    }
}
