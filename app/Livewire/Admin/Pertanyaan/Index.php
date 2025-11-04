<?php

namespace App\Livewire\Admin\Pertanyaan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kuesioner;
use App\Models\Option;
use App\Models\Question;


class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $kuesionerId;
    public $kuesioner;
    public $search = '';

    public $pertanyaan;
    // HAPUS property $pertanyaans
    public $pertanyaanId;
    public $question_code;
    public $text;
    public $input_type;
    public $parentCcode;
    public $parentValue;
    public $tanyas = [];
    public $isEdit = false;
    public $deleteId;
    public $is_custom_input;
    public $maxValue;

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
        // $this->opsiJawaban = [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // public function openCreateModal()
    // {
    //     $this->reset(['pertanyaanId', 'pertanyaan', 'tipe_pertanyaan', 'is_required', 'urutan', 'kondisi_tampil', 'isEdit', 'opsiJawaban']);
    //     $this->opsiJawaban = [];
    //     $this->is_required = true;
    //     $this->tipe_pertanyaan = 'select';
    //     $this->urutan = Question::where('quisioner_id', $this->kuesionerId)->max('urutan') + 1 ?? 1;
    //     $this->resetValidation();
    //     $this->dispatch('show-form-modal');
    // }

    public function openEditModal($id)
    {
        $pertanyaan = Question::findOrFail($id);
        $this->pertanyaanId = $pertanyaan->id;
        $this->question_code = $pertanyaan->question_code;
        $this->text = $pertanyaan->text;
        $this->input_type = $pertanyaan->input_type;
        $this->parentCcode = $pertanyaan->conditional_parent_code;
        $this->parentValue = $pertanyaan->conditional_parent_value;
        $this->tanyas = Question::where('quisioner_id', $this->kuesionerId)->get();
        $idpertanyaanSyarat = Question::where('question_code', $this->parentCcode)->value('id');
        $this->opsiJawaban = Option::where('question_id', $idpertanyaanSyarat)->get();
        // dd($pertanyaan->id);
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }
    public function openEditOpsiModal($id)
    {
        $pertanyaan = Question::findOrFail($id);
        $this->pertanyaanId = $pertanyaan->id;
        $this->question_code = $pertanyaan->question_code;
        $this->text = $pertanyaan->text;
        $this->input_type = $pertanyaan->input_type;
        $this->parentCcode = $pertanyaan->conditional_parent_code;
        $this->parentValue = $pertanyaan->conditional_parent_value;
        $this->opsiJawaban = Option::where('question_id', $this->pertanyaanId)->get();
        $this->maxValue = collect($this->opsiJawaban)->max('value') ?? 0;
        // dd($pertanyaan->id);
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal-opsi');
    }
    public function updatedParentCcode($value, $key)
    {
        $idpertanyaanSyarat = Question::where('question_code', $value);
        $this->parentValue = '';
        $this->opsiJawaban = Option::where('question_id', $idpertanyaanSyarat->value('id'))->get();
        // dd($value, $key);
    }
    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $pertanyaan = Question::findOrFail($this->pertanyaanId);
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
            $pertanyaan = Question::create([
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
        $pertanyaan = Question::find($this->deleteId);

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
        $pertanyaans = Question::where('quisioner_id', $this->kuesionerId)
            ->when($this->search, function ($query) {
                $query->where('text', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id')
            ->paginate(10);

        return view('admin.pertanyaan.index', [
            'pertanyaans' => $pertanyaans,
            'semuaType' => Question::all()->groupBy('input_type'),


        ]);
    }
}
