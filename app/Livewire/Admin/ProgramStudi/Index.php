<?php

namespace App\Livewire\Admin\ProgramStudi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProgramStudi;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $programStudiId;
    public $nama_prodi;
    public $isEdit = false;
    public $deleteId;

    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'nama_prodi' => [
                'required',
                'string',
                'max:255',
                'unique:program_studi,nama_prodi,' . ($this->programStudiId ?? 'NULL')
            ],
        ];
    }

    protected $messages = [
        'nama_prodi.required' => 'Nama Program Studi wajib diisi.',
        'nama_prodi.unique' => 'Nama Program Studi sudah ada.',
        'nama_prodi.max' => 'Nama Program Studi maksimal 255 karakter.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['programStudiId', 'nama_prodi', 'isEdit']);
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function openEditModal($id)
    {
        $programStudi = ProgramStudi::findOrFail($id);
        $this->programStudiId = $programStudi->id;
        $this->nama_prodi = $programStudi->nama_prodi;
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $programStudi = ProgramStudi::findOrFail($this->programStudiId);
            $programStudi->update([
                'nama_prodi' => $this->nama_prodi,
            ]);
            session()->flash('success', 'Program Studi berhasil diupdate.');
        } else {
            ProgramStudi::create([
                'nama_prodi' => $this->nama_prodi,
            ]);
            session()->flash('success', 'Program Studi berhasil ditambahkan.');
        }

        $this->reset(['programStudiId', 'nama_prodi', 'isEdit']);
        $this->dispatch('hide-form-modal');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $programStudi = ProgramStudi::find($this->deleteId);

        if ($programStudi) {
            // Cek apakah program studi memiliki alumni
            if ($programStudi->alumni()->count() > 0) {
                session()->flash('error', 'Program Studi tidak dapat dihapus karena masih memiliki data alumni.');
            } else {
                $programStudi->delete();
                session()->flash('success', 'Program Studi berhasil dihapus.');
            }
        }

        $this->deleteId = null;
        $this->dispatch('hide-delete-modal');
    }

    public function render()
    {
        $programStudi = ProgramStudi::query()
            ->when($this->search, function ($query) {
                $query->where('nama_prodi', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.program-studi.index', [
            'programStudi' => $programStudi
        ]);
    }
}
