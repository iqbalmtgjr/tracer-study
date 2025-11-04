<?php

namespace App\Imports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlumniImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row['nim']);
        // $cek = $this->convertExcelDate($row['tanggal_lahir']);
        // dd($cek);
        return new Alumni([
            'kode_pt' => '113062',
            'kode_prodi' => $row['kode_prodi'],
            'nim' => $row['nim'],
            'nik' => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $this->convertExcelDate($row['tanggal_lahir']),
            'no_hp' => $row['no_hp'],
            'email' => $row['email'],
            'tahun_lulus' => $row['tahun_lulus'],
            'npwp' => $row['npwp'] ?? null,
        ]);
    }

    /**
     * Konversi tanggal Excel (dd/mm/yyyy atau serial number) ke format Y-m-d
     */
    private function convertExcelDate($dateString)
    {
        // Jika input adalah serial number Excel (angka)
        if (is_numeric($dateString)) {
            // Excel serial date dimulai dari 1900-01-01 (serial 1 = 1900-01-01)
            // Tapi ada bug di Excel dimana 1900 dianggap leap year, jadi kita gunakan 1970-01-01 sebagai base
            $timestamp = ($dateString - 25569) * 86400; // 25569 adalah serial untuk 1970-01-01
            try {
                return \Carbon\Carbon::createFromTimestamp($timestamp)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Jika input adalah string tanggal dd/mm/yyyy
        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            // Jika format tidak sesuai, coba parse dengan Carbon
            try {
                return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    public function rules(): array
    {
        return [
            'nim' => 'required|unique:alumni,nim',
            'nik' => 'nullable|unique:alumni,nik',
            'no_hp' => 'nullable',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'tahun_lulus' => 'required|max:4',
            'email' => 'nullable|email|unique:users,email',
            'npwp' => 'nullable|string|max:100|unique:alumni,npwp',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'kode_pt.required' => 'Kode Perguruan Tinggi wajib diisi.',
            'kode_prodi.required' => 'Program studi wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'npwp.unique' => 'NPWP sudah terdaftar.',
        ];
    }
}
