<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $programStudi = [
            'Pendidikan Guru Sekolah Dasar',
            'Pendidikan Bahasa dan Sastra Indonesia',
            'Pendidikan Biologi',
            'Pendidikan Komputer',
            'Pendidikan Ekonomi',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Pendidikan Bahasa Inggris',
            'Pendidikan Matematika',
            'Pendidikan Anak Usia Dini',
        ];

        foreach ($programStudi as $prodi) {
            DB::table('program_studi')->insert([
                'nama_prodi' => $prodi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
