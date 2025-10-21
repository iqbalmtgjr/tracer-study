<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\PertanyaanGrid;

class KuesionerSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Kuesioner
        $kuesioner = Kuesioner::create([
            'judul' => 'Tracer Study Alumni STKIP Persada Khatulistiwa',
            'deskripsi' => 'Kuesioner untuk melacak jejak alumni STKIP Persada Khatulistiwa',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(6),
            'is_active' => true,
        ]);

        // Pertanyaan 1: Status saat ini
        $p1 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f8',
            'pertanyaan' => 'Jelaskan status Anda saat ini?',
            'tipe_pertanyaan' => 'radio',
            'is_required' => true,
            'urutan' => 1,
        ]);

        OpsiJawaban::create(['pertanyaan_id' => $p1->id, 'kode_opsi' => '1', 'opsi' => 'Bekerja (full time / part time)', 'urutan' => 1, 'trigger_question' => 'bekerja']);
        OpsiJawaban::create(['pertanyaan_id' => $p1->id, 'kode_opsi' => '2', 'opsi' => 'Belum memungkinkan bekerja', 'urutan' => 2]);
        OpsiJawaban::create(['pertanyaan_id' => $p1->id, 'kode_opsi' => '3', 'opsi' => 'Wiraswasta', 'urutan' => 3, 'trigger_question' => 'wiraswasta']);
        OpsiJawaban::create(['pertanyaan_id' => $p1->id, 'kode_opsi' => '4', 'opsi' => 'Melanjutkan Pendidikan', 'urutan' => 4, 'trigger_question' => 'studi_lanjut']);
        OpsiJawaban::create(['pertanyaan_id' => $p1->id, 'kode_opsi' => '5', 'opsi' => 'Tidak kerja tetapi sedang mencari kerja', 'urutan' => 5]);

        // Pertanyaan 2: Berapa bulan mendapat pekerjaan (conditional: jika bekerja)
        $p2 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f502',
            'pertanyaan' => 'Dalam berapa bulan Anda mendapatkan pekerjaan pertama setelah lulus?',
            'tipe_pertanyaan' => 'number',
            'is_required' => true,
            'urutan' => 2,
            'kondisi_tampil' => ['f8' => ['1', '3']], // tampil jika f8 = 1 atau 3
            'keterangan' => 'bulan',
        ]);

        // Pertanyaan 3: Rata-rata pendapatan
        $p3 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f505',
            'pertanyaan' => 'Berapa rata-rata pendapatan Anda per bulan? (take home pay)',
            'tipe_pertanyaan' => 'number',
            'is_required' => false,
            'urutan' => 3,
            'kondisi_tampil' => ['f8' => ['1', '3']],
            'keterangan' => 'Rupiah',
        ]);

        // Pertanyaan 4: Jenis perusahaan (conditional: jika bekerja)
        $p4 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f1101',
            'pertanyaan' => 'Apa jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?',
            'tipe_pertanyaan' => 'radio',
            'is_required' => true,
            'urutan' => 4,
            'kondisi_tampil' => ['f8' => ['1']],
        ]);

        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '1', 'opsi' => 'Instansi pemerintah', 'urutan' => 1]);
        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '2', 'opsi' => 'Organisasi non-profit/Lembaga Swadaya Masyarakat', 'urutan' => 2]);
        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '3', 'opsi' => 'Perusahaan swasta', 'urutan' => 3]);
        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '4', 'opsi' => 'Wiraswasta/perusahaan sendiri', 'urutan' => 4]);
        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '6', 'opsi' => 'BUMN/BUMD', 'urutan' => 5]);
        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '7', 'opsi' => 'Institusi/Organisasi Multilateral', 'urutan' => 6]);
        OpsiJawaban::create(['pertanyaan_id' => $p4->id, 'kode_opsi' => '5', 'opsi' => 'Lainnya', 'urutan' => 7, 'has_input' => true, 'input_type' => 'text']);

        // Pertanyaan 5: Nama perusahaan
        $p5 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f5b',
            'pertanyaan' => 'Apa nama perusahaan/kantor tempat Anda bekerja?',
            'tipe_pertanyaan' => 'text',
            'is_required' => false,
            'urutan' => 5,
            'kondisi_tampil' => ['f8' => ['1']],
        ]);

        // Pertanyaan 6: Sumber biaya kuliah
        $p6 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f1201',
            'pertanyaan' => 'Sebutkan sumber dana dalam pembiayaan kuliah?',
            'tipe_pertanyaan' => 'radio',
            'is_required' => true,
            'urutan' => 6,
        ]);

        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '1', 'opsi' => 'Biaya Sendiri/Keluarga', 'urutan' => 1]);
        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '2', 'opsi' => 'Beasiswa ADIK', 'urutan' => 2]);
        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '3', 'opsi' => 'Beasiswa BIDIKMISI', 'urutan' => 3]);
        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '4', 'opsi' => 'Beasiswa PPA', 'urutan' => 4]);
        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '5', 'opsi' => 'Beasiswa AFIRMASI', 'urutan' => 5]);
        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '6', 'opsi' => 'Beasiswa Perusahaan/Swasta', 'urutan' => 6]);
        OpsiJawaban::create(['pertanyaan_id' => $p6->id, 'kode_opsi' => '7', 'opsi' => 'Lainnya', 'urutan' => 7, 'has_input' => true, 'input_type' => 'text']);

        // Pertanyaan 7: Hubungan bidang studi dengan pekerjaan
        $p7 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f14',
            'pertanyaan' => 'Seberapa erat hubungan bidang studi dengan pekerjaan Anda?',
            'tipe_pertanyaan' => 'radio',
            'is_required' => true,
            'urutan' => 7,
            'kondisi_tampil' => ['f8' => ['1']],
        ]);

        OpsiJawaban::create(['pertanyaan_id' => $p7->id, 'kode_opsi' => '1', 'opsi' => 'Sangat Erat', 'urutan' => 1]);
        OpsiJawaban::create(['pertanyaan_id' => $p7->id, 'kode_opsi' => '2', 'opsi' => 'Erat', 'urutan' => 2]);
        OpsiJawaban::create(['pertanyaan_id' => $p7->id, 'kode_opsi' => '3', 'opsi' => 'Cukup Erat', 'urutan' => 3]);
        OpsiJawaban::create(['pertanyaan_id' => $p7->id, 'kode_opsi' => '4', 'opsi' => 'Kurang Erat', 'urutan' => 4]);
        OpsiJawaban::create(['pertanyaan_id' => $p7->id, 'kode_opsi' => '5', 'opsi' => 'Tidak Sama Sekali', 'urutan' => 5]);

        // Pertanyaan 8: Cara mencari pekerjaan (multiple checkbox)
        $p8 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f4',
            'pertanyaan' => 'Bagaimana anda mencari pekerjaan tersebut? (Jawaban bisa lebih dari satu)',
            'tipe_pertanyaan' => 'checkbox',
            'is_required' => true,
            'allow_multiple' => true,
            'urutan' => 8,
            'kondisi_tampil' => ['f8' => ['1', '3']],
        ]);

        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f401', 'opsi' => 'Melalui iklan di koran/majalah, brosur', 'urutan' => 1]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f402', 'opsi' => 'Melamar ke perusahaan tanpa mengetahui lowongan yang ada', 'urutan' => 2]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f403', 'opsi' => 'Pergi ke bursa/pameran kerja', 'urutan' => 3]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f404', 'opsi' => 'Mencari lewat internet/iklan online/milis', 'urutan' => 4]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f405', 'opsi' => 'Dihubungi oleh perusahaan', 'urutan' => 5]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f411', 'opsi' => 'Melalui relasi (dosen, orang tua, saudara, teman)', 'urutan' => 6]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f412', 'opsi' => 'Membangun bisnis sendiri', 'urutan' => 7]);
        OpsiJawaban::create(['pertanyaan_id' => $p8->id, 'kode_opsi' => 'f415', 'opsi' => 'Lainnya', 'urutan' => 8, 'has_input' => true, 'input_type' => 'text']);

        // Pertanyaan 9: Grid/Matrix - Kompetensi
        $p9 = Pertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'kode_pertanyaan' => 'f176',
            'pertanyaan' => 'Pada saat lulus, pada tingkat mana kompetensi di bawah ini anda kuasai? (A) Dan pada saat ini, pada tingkat mana kompetensi di bawah ini diperlukan dalam pekerjaan? (B)',
            'tipe_pertanyaan' => 'grid',
            'is_required' => true,
            'urutan' => 9,
        ]);

        // Grid rows
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Etika', 'kode_row' => 'f1761', 'urutan' => 1]);
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Keahlian berdasarkan bidang ilmu', 'kode_row' => 'f1763', 'urutan' => 2]);
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Bahasa Inggris', 'kode_row' => 'f1765', 'urutan' => 3]);
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Penggunaan Teknologi Informasi', 'kode_row' => 'f1767', 'urutan' => 4]);
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Komunikasi', 'kode_row' => 'f1769', 'urutan' => 5]);
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Kerja sama tim', 'kode_row' => 'f1771', 'urutan' => 6]);
        PertanyaanGrid::create(['pertanyaan_id' => $p9->id, 'row_label' => 'Pengembangan diri', 'kode_row' => 'f1773', 'urutan' => 7]);
    }
}
