<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->string('posisi');
            $table->enum('jenis_pekerjaan', [
                'full_time',
                'part_time',
                'freelance',
                'wirausaha',
                'kontrak'
            ]);
            $table->decimal('gaji', 12, 2)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('masih_bekerja')->default(false);
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->string('kota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pekerjaan');
    }
};
