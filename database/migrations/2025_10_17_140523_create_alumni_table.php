<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pt');
            $table->string('kode_prodi');
            $table->string('nim', 20)->unique();
            $table->string('nik', 50)->unique();
            $table->string('npwp', 100)->unique()->nullable();
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->string('no_hp', 15);
            $table->string('email');
            $table->string('tahun_lulus', 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
