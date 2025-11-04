<?php

namespace App\Livewire\Admin\Kuesioner;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kuesioner;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $kuesionerId;
    public $judul;
    public $deskripsi;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $is_active = true;
    public $isEdit = false;
    public $deleteId;

    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'judul.required' => 'Judul kuesioner wajib diisi.',
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['kuesionerId', 'judul', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'is_active', 'isEdit']);
        $this->is_active = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function openEditModal($id)
    {
        $kuesioner = Kuesioner::findOrFail($id);
        $this->kuesionerId = $kuesioner->id;
        $this->judul = $kuesioner->judul;
        $this->deskripsi = $kuesioner->deskripsi;
        $this->tanggal_mulai = $kuesioner->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $kuesioner->tanggal_selesai->format('Y-m-d');
        $this->is_active = $kuesioner->is_active;
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $kuesioner = Kuesioner::findOrFail($this->kuesionerId);
            $kuesioner->update([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Kuesioner berhasil diupdate.');
        } else {
            Kuesioner::create([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Kuesioner berhasil ditambahkan.');
        }

        $this->reset(['kuesionerId', 'judul', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'is_active', 'isEdit']);
        $this->dispatch('hide-form-modal');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-modal');
    }

    // public function delete()
    // {
    //     $kuesioner = Kuesioner::find($this->deleteId);

    //     if ($kuesioner) {
    //         // Cek apakah kuesioner memiliki responden
    //         if ($kuesioner->responden()->count() > 0) {
    //             session()->flash('error', 'Kuesioner tidak dapat dihapus karena sudah memiliki responden.');
    //         } else {
    //             $kuesioner->delete();
    //             session()->flash('success', 'Kuesioner berhasil dihapus.');
    //         }
    //     }

    //     $this->deleteId = null;
    //     $this->dispatch('hide-delete-modal');
    // }

    public function toggleStatus($id)
    {
        $kuesioner = Kuesioner::findOrFail($id);
        $kuesioner->update(['is_active' => !$kuesioner->is_active]);

        $status = $kuesioner->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Kuesioner berhasil {$status}.");
    }

    public function render()
    {
        $kuesioner = Kuesioner::query()
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('admin.kuesioner.index', [
            'kuesioner' => $kuesioner
        ]);
    }
}
