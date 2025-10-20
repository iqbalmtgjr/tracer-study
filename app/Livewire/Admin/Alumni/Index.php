<?php

namespace App\Livewire\Admin\Alumni;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alumni;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $alumniId;
    public $userId;
    public $nim;
    public $nama_lengkap;
    public $jenis_kelamin;
    public $program_studi_id;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $no_hp;
    public $email;
    public $alamat;
    public $tahun_lulus;
    public $ipk;
    public $isEdit = false;
    public $deleteId;

    protected $queryString = ['search'];

    protected function rules()
    {
        $rules = [
            'nim' => [
                'required',
                'string',
                'max:20',
                'unique:alumni,nim,' . ($this->alumniId ?? 'NULL')
            ],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'program_studi_id' => 'required|exists:program_studi,id',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'tahun_lulus' => 'required|string|max:4',
            'ipk' => 'nullable|numeric|min:0|max:4',
        ];

        if (!$this->isEdit) {
            $rules['email'] = 'nullable|email|max:255|unique:users,email';
        } else {
            $rules['email'] = 'nullable|email|max:255|unique:users,email,' . $this->userId;
        }

        return $rules;
    }

    protected $messages = [
        'nim.required' => 'NIM wajib diisi.',
        'nim.unique' => 'NIM sudah terdaftar.',
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'program_studi_id.required' => 'Program studi wajib dipilih.',
        'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
        'ipk.numeric' => 'IPK harus berupa angka.',
        'ipk.min' => 'IPK minimal 0.',
        'ipk.max' => 'IPK maksimal 4.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['alumniId', 'userId', 'nim', 'nama_lengkap', 'jenis_kelamin', 'program_studi_id', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'email', 'alamat', 'tahun_lulus', 'ipk', 'isEdit']);
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function openEditModal($id)
    {
        $alumni = Alumni::findOrFail($id);
        $this->alumniId = $alumni->id;
        $this->userId = $alumni->user_id;
        $this->nim = $alumni->nim;
        $this->nama_lengkap = $alumni->nama_lengkap;
        $this->jenis_kelamin = $alumni->jenis_kelamin;
        $this->program_studi_id = $alumni->program_studi_id;
        $this->tempat_lahir = $alumni->tempat_lahir;
        $this->tanggal_lahir = $alumni->tanggal_lahir->format('Y-m-d');
        $this->no_hp = $alumni->no_hp;
        $this->email = $alumni->email;
        $this->alamat = $alumni->alamat;
        $this->tahun_lulus = $alumni->tahun_lulus;
        $this->ipk = $alumni->ipk;
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $alumni = Alumni::findOrFail($this->alumniId);
            $user = User::findOrFail($this->userId);

            // Update user
            $user->update([
                'name' => $this->nama_lengkap,
                'email' => $this->email,
            ]);

            // Update alumni
            $alumni->update([
                'nim' => $this->nim,
                'nama_lengkap' => $this->nama_lengkap,
                'jenis_kelamin' => $this->jenis_kelamin,
                'program_studi_id' => $this->program_studi_id,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'alamat' => $this->alamat,
                'tahun_lulus' => $this->tahun_lulus,
                'ipk' => $this->ipk,
            ]);

            session()->flash('success', 'Data alumni berhasil diupdate.');
        } else {
            // Create user first
            $user = User::create([
                'name' => $this->nama_lengkap,
                'email' => $this->email ?: $this->nim . '@stkip.ac.id',
                'password' => Hash::make('password123'), // Default password
            ]);

            // Assign role
            $user->assignRole('alumni');

            // Create alumni
            Alumni::create([
                'user_id' => $user->id,
                'program_studi_id' => $this->program_studi_id,
                'nim' => $this->nim,
                'nama_lengkap' => $this->nama_lengkap,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'alamat' => $this->alamat,
                'tahun_lulus' => $this->tahun_lulus,
                'ipk' => $this->ipk,
            ]);

            session()->flash('success', 'Alumni berhasil ditambahkan.');
        }

        $this->reset(['alumniId', 'userId', 'nim', 'nama_lengkap', 'jenis_kelamin', 'program_studi_id', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'email', 'alamat', 'tahun_lulus', 'ipk', 'isEdit']);
        $this->dispatch('hide-form-modal');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $alumni = Alumni::find($this->deleteId);

        if ($alumni) {
            // Delete related data first
            $alumni->responden()->delete();
            $alumni->riwayatPekerjaan()->delete();
            $alumni->riwayatPendidikan()->delete();

            // Delete user
            $user = $alumni->user;
            if ($user) {
                $user->delete();
            }

            // Delete alumni
            $alumni->delete();

            session()->flash('success', 'Alumni berhasil dihapus.');
        }

        $this->deleteId = null;
        $this->dispatch('hide-delete-modal');
    }

    public function render()
    {
        $alumni = Alumni::query()
            ->with('programStudi')
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%')
                    ->orWhereHas('programStudi', function ($q) {
                        $q->where('nama_prodi', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        $programStudi = ProgramStudi::all();

        return view('admin.alumni.index', [
            'alumni' => $alumni,
            'programStudi' => $programStudi
        ]);
    }
}
