<?php

namespace App\Livewire\Admin\Alumni;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Alumni;
use App\Models\ProgramStudi;


use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlumniImport;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $alumniId;
    public $kodept;
    public $kodeprodi;
    public $nim;
    public $nik;
    public $npwp;
    public $nama_lengkap;
    public $no_hp;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $email;
    public $tahun_lulus;
    public $isEdit = false;
    public $deleteId;
    public $importFile;

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
            'nik' => 'nullable|string|max:50|unique:alumni,nik,' . ($this->alumniId ?? 'NULL'),
            'npwp' => 'nullable|string|max:100|unique:alumni,npwp,' . ($this->alumniId ?? 'NULL'),
            'kodept' => 'nullable|string|max:100',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'email' => 'nullable|email|max:255',
            'tahun_lulus' => 'required|string|max:4',
        ];

        if (!$this->isEdit) {
            $rules['email'] = 'nullable|email|max:255|unique:users,email';
        } else {
            $rules['email'] = 'nullable|email|max:255|unique:users,email,' . $this->alumniId;
        }

        return $rules;
    }

    protected $messages = [
        'nim.required' => 'NIM wajib diisi.',
        'nim.unique' => 'NIM sudah terdaftar.',
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'kodept.required' => 'Kode PT wajib diisi.',
        'kodeprodi.required' => 'Kode Program studi wajib diisi.',
        'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['alumniId', 'nim', 'nik', 'npwp', 'nama_lengkap', 'kodept', 'kodeprodi', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'email', 'tahun_lulus', 'isEdit']);
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function openEditModal($id)
    {
        $alumni = Alumni::findOrFail($id);
        $this->alumniId = $alumni->id;
        // dd($this->alumniId);
        $this->kodeprodi = $alumni->kode_prodi;
        $this->nim = $alumni->nim;
        $this->nik = $alumni->nik;
        $this->nama_lengkap = $alumni->nama_lengkap;
        $this->tempat_lahir = $alumni->tempat_lahir;
        $this->tanggal_lahir = $alumni->tanggal_lahir->format('Y-m-d');
        $this->no_hp = $alumni->no_hp;
        $this->email = $alumni->email;
        $this->tahun_lulus = $alumni->tahun_lulus;
        $this->isEdit = true;
        $this->resetValidation();
        $this->dispatch('show-form-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $alumni = Alumni::findOrFail($this->alumniId);
            // Update alumni
            $alumni->update([
                'kode_pt' => '113062',
                'kode_prodi' => $this->kodeprodi,
                'nim' => $this->nim,
                'nik' => $this->nik,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'tahun_lulus' => $this->tahun_lulus,
            ]);

            session()->flash('success', 'Data alumni berhasil diupdate.');
        } else {
            // Create user first
            // $user = User::create([
            //     'name' => $this->nama_lengkap,
            //     'email' => $this->email ?: $this->nim . '@stkip.ac.id',
            //     'password' => Hash::make('password123'), // Default password
            // ]);

            // Assign role
            // $user->assignRole('alumni');

            // Create alumni
            Alumni::create([
                'kode_pt' => '113062',
                'kode_prodi' => $this->kodeprodi,
                'nim' => $this->nim,
                'nik' => $this->nik,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'tahun_lulus' => $this->tahun_lulus,
            ]);

            session()->flash('success', 'Alumni berhasil ditambahkan.');
        }

        $this->reset(['nim', 'nik', 'npwp',  'nama_lengkap', 'kodept', 'kodeprodi', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'email', 'tahun_lulus', 'isEdit']);
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

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls',
        ], [
            'importFile.required' => 'File Excel wajib dipilih.',
            'importFile.mimes' => 'File harus berformat .xlsx atau .xls',
            // 'importFile.max' => 'Ukuran file maksimal 2MB.'
        ]);

        try {
            // Pastikan file ada
            if (!$this->importFile) {
                session()->flash('error', 'File tidak ditemukan.');
                return;
            }
            // dd($this->importFile->getRealPath());

            Excel::import(new AlumniImport, $this->importFile->getRealPath());

            session()->flash('success', 'Data alumni berhasil diimpor.');
            $this->reset('importFile');
            $this->dispatch('hide-import-modal');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            session()->flash('error', 'Terjadi kesalahan validasi: ' . implode('; ', $errors));
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function openImportModal()
    {
        $this->reset('importFile');
        $this->dispatch('show-import-modal');
    }

    public function render()
    {
        $alumni = Alumni::query()
            ->with('programStudi')
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%')
                    ->orWhereHas('programStudi', function ($q) {
                        $q->where('program_studi', 'like', '%' . $this->search . '%');
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
