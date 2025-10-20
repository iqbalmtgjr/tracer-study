<?php

namespace App\Livewire\Admin\Pertanyaan;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $kuesionerId;
    public $kuesioner;
    public $search = '';
    public $pertanyaanId;
    public $pertanyaan;
    // HAPUS property $pertanyaans
    public $tipe_pertanyaan = 'select';
    public $is_required = true;
    public $urutan = 0;
    public $kondisi_tampil;
    public $isEdit = false;
    public $deleteId;

    // Untuk opsi jawaban
    public $opsiJawaban = [];
    public $showOpsiForm = false;

    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'pertanyaan' => 'required|string',
            'tipe_pertanyaan' => 'required|in:select,textarea',
            'is_required' => 'boolean',
            'urutan' => 'integer|min:0',
            'kondisi_tampil' => 'nullable|string',
        ];
    }

    protected $messages = [
        'pertanyaan.required' => 'Pertanyaan wajib diisi.',
        'tipe_pertanyaan.required' => 'Tipe pertanyaan wajib dipilih.',
        'tipe_pertanyaan.in' => 'Tipe pertanyaan tidak valid.',
    ];

    public function mount($kuesioner)
    {
        $this->kuesionerId = $kuesioner;
        $this->kuesioner = Kuesioner::findOrFail($kuesioner);
        $this->opsiJawaban = [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['pertanyaanId', 'pertanyaan', 'tipe_pertanyaan', 'is_required', 'urutan', 'kondisi_tampil', 'isEdit', 'opsiJawaban']);
        $this->opsiJawaban = [];
        $this->is_required = true;
        $this->tipe_pertanyaan = 'select';
        $this->urutan = Pertanyaan::where('kuesioner_id', $this->kuesionerId)->max('urutan') + 1 ?? 1;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function openEditModal($id)
    {
        $pertanyaan = Pertanyaan::with('opsiJawaban')->findOrFail($id);
        $this->pertanyaanId = $pertanyaan->id;
        $this->pertanyaan = $pertanyaan->pertanyaan;
        $this->tipe_pertanyaan = $pertanyaan->tipe_pertanyaan;
        $this->is_required = $pertanyaan->is_required;
        $this->urutan = $pertanyaan->urutan;
        $this->kondisi_tampil = $pertanyaan->kondisi_tampil;
        $this->opsiJawaban = $pertanyaan->opsiJawaban->map(function ($opsi) {
            return [
                'id' => $opsi->id,
                'opsi' => $opsi->opsi,
                'nilai' => $opsi->nilai,
                'urutan' => $opsi->urutan,
            ];
        })->toArray();
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $pertanyaan = Pertanyaan::findOrFail($this->pertanyaanId);
            $pertanyaan->update([
                'pertanyaan' => $this->pertanyaan,
                'tipe_pertanyaan' => $this->tipe_pertanyaan,
                'is_required' => $this->is_required,
                'urutan' => $this->urutan,
                'kondisi_tampil' => $this->kondisi_tampil,
            ]);

            // Update opsi jawaban
            $pertanyaan->opsiJawaban()->delete();
            foreach ($this->opsiJawaban as $opsi) {
                $pertanyaan->opsiJawaban()->create($opsi);
            }

            session()->flash('success', 'Pertanyaan berhasil diupdate.');
        } else {
            $pertanyaan = Pertanyaan::create([
                'kuesioner_id' => $this->kuesionerId,
                'pertanyaan' => $this->pertanyaan,
                'tipe_pertanyaan' => $this->tipe_pertanyaan,
                'is_required' => $this->is_required,
                'urutan' => $this->urutan,
                'kondisi_tampil' => $this->kondisi_tampil,
            ]);

            // Buat opsi jawaban
            foreach ($this->opsiJawaban as $opsi) {
                $pertanyaan->opsiJawaban()->create($opsi);
            }

            session()->flash('success', 'Pertanyaan berhasil ditambahkan.');
        }

        $this->reset(['pertanyaanId', 'pertanyaan', 'tipe_pertanyaan', 'is_required', 'urutan', 'kondisi_tampil', 'isEdit', 'opsiJawaban']);
        $this->opsiJawaban = [];
        $this->dispatch('hide-form-modal');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $pertanyaan = Pertanyaan::find($this->deleteId);

        if ($pertanyaan) {
            // Cek apakah pertanyaan memiliki jawaban
            if ($pertanyaan->jawaban()->count() > 0) {
                session()->flash('error', 'Pertanyaan tidak dapat dihapus karena sudah memiliki jawaban.');
            } else {
                $pertanyaan->delete();
                session()->flash('success', 'Pertanyaan berhasil dihapus.');
            }
        }

        $this->deleteId = null;
        $this->dispatch('hide-delete-modal');
    }

    public function addOpsi()
    {
        $this->opsiJawaban[] = [
            'id' => null,
            'opsi' => '',
            'nilai' => null,
            'urutan' => count($this->opsiJawaban) + 1,
        ];
    }

    public function removeOpsi($index)
    {
        unset($this->opsiJawaban[$index]);
        $this->opsiJawaban = array_values($this->opsiJawaban);
        // Reorder urutan
        foreach ($this->opsiJawaban as $i => &$opsi) {
            $opsi['urutan'] = $i + 1;
        }
    }

    public function updatedTipePertanyaan()
    {
        if ($this->tipe_pertanyaan === 'radio') {
            $this->showOpsiForm = true;
        } else {
            $this->showOpsiForm = false;
            $this->opsiJawaban = [];
        }
    }

    public function render()
    {
        // Pindahkan query pagination ke sini
        $pertanyaans = Pertanyaan::with('opsiJawaban')
            ->where('kuesioner_id', $this->kuesionerId)
            ->when($this->search, function ($query) {
                $query->where('pertanyaan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('urutan')
            ->paginate(10);

        return view('admin.pertanyaan.index', [
            'pertanyaans' => $pertanyaans
        ]);
    }
}
