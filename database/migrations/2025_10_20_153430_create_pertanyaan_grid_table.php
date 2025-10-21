<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaan_grid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->onDelete('cascade');
            $table->string('row_label'); // label baris (misal: Etika, Keahlian, dll)
            $table->string('kode_row')->nullable(); // kode seperti f1761, f1762
            $table->string('column_group')->nullable(); // untuk grup kolom (A atau B)
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_grid');
    }
};
