<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use App\Models\Kuesioner;
use App\Models\Responden;
use App\Models\Alumni;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $selectedKuesioner;
    public $filterProgramStudi;
    public $filterTahunLulus;
    public $filterStatus;

    public function mount()
    {
        // Set default kuesioner aktif
        $this->selectedKuesioner = Kuesioner::active()->first()?->id;
    }

    public function updatedSelectedKuesioner()
    {
        // Reset filter ketika kuesioner berubah
        $this->reset(['filterProgramStudi', 'filterTahunLulus', 'filterStatus']);
    }

    public function getLaporanData()
    {
        if (!$this->selectedKuesioner) {
            return collect();
        }

        $query = Responden::with(['alumni.programStudi', 'kuesioner'])
            ->where('kuesioner_id', $this->selectedKuesioner)
            ->when($this->filterProgramStudi, function ($q) {
                $q->whereHas('alumni.programStudi', function ($query) {
                    $query->where('kode_program_studi', $this->filterProgramStudi);
                });
            })
            ->when($this->filterTahunLulus, function ($q) {
                $q->whereHas('alumni', function ($query) {
                    $query->where('tahun_lulus', $this->filterTahunLulus);
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            });

        return $query->get();
    }

    public function getStatistikData()
    {
        if (!$this->selectedKuesioner) {
            return [
                'total_responden' => 0,
                'responden_selesai' => 0,
                'responden_draft' => 0,
                'persentase_kelulusan' => 0,
            ];
        }

        $total = Responden::where('kuesioner_id', $this->selectedKuesioner)->count();
        $selesai = Responden::where('kuesioner_id', $this->selectedKuesioner)
            ->where('status', 'selesai')->count();
        $draft = $total - $selesai;

        $persentase = $total > 0 ? round(($selesai / $total) * 100, 2) : 0;

        return [
            'total_responden' => $total,
            'responden_selesai' => $selesai,
            'responden_draft' => $draft,
            'persentase_kelulusan' => $persentase,
        ];
    }

    public function getChartData()
    {
        if (!$this->selectedKuesioner) {
            return [
                'program_studi' => [],
                'tahun_lulus' => [],
            ];
        }

        // Data berdasarkan program studi
        $programStudiData = Responden::select('alumni.kode_prodi', DB::raw('count(*) as total'))
            ->join('alumni', 'responden.alumni_id', '=', 'alumni.id')
            ->where('responden.kuesioner_id', $this->selectedKuesioner)
            ->where('responden.status', 'selesai')
            ->groupBy('alumni.kode_prodi')
            ->with('alumni.programStudi')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->alumni->programStudi->nama_prodi,
                    'total' => $item->total,
                ];
            });

        // Data berdasarkan tahun lulus
        $tahunLulusData = Responden::select('alumni.tahun_lulus', DB::raw('count(*) as total'))
            ->join('alumni', 'responden.alumni_id', '=', 'alumni.id')
            ->where('responden.kuesioner_id', $this->selectedKuesioner)
            ->where('responden.status', 'selesai')
            ->groupBy('alumni.tahun_lulus')
            ->orderBy('alumni.tahun_lulus')
            ->get()
            ->map(function ($item) {
                return [
                    'tahun' => $item->tahun_lulus,
                    'total' => $item->total,
                ];
            });

        return [
            'program_studi' => $programStudiData,
            'tahun_lulus' => $tahunLulusData,
        ];
    }

    public function exportExcel()
    {
        // Implementasi export Excel akan ditambahkan nanti
        session()->flash('info', 'Fitur export Excel akan segera ditambahkan.');
    }

    public function exportPdf()
    {
        // Implementasi export PDF akan ditambahkan nanti
        session()->flash('info', 'Fitur export PDF akan segera ditambahkan.');
    }

    public function render()
    {
        $kuesioner = Kuesioner::all();
        $programStudi = ProgramStudi::all();
        $tahunLulus = Alumni::select('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');

        $laporanData = $this->getLaporanData();
        $statistik = $this->getStatistikData();
        $chartData = $this->getChartData();

        return view('admin.laporan.index', [
            'kuesioner' => $kuesioner,
            'programStudi' => $programStudi,
            'tahunLulus' => $tahunLulus,
            'laporanData' => $laporanData,
            'statistik' => $statistik,
            'chartData' => $chartData,
        ]);
    }
}
