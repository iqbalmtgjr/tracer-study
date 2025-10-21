<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opsi_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->onDelete('cascade');
            $table->string('kode_opsi')->nullable(); // untuk kode opsi seperti (1), (2), dst
            $table->string('opsi');
            $table->integer('nilai')->nullable(); // untuk scoring
            $table->integer('urutan')->default(0);
            $table->boolean('has_input')->default(false); // jika ada input tambahan (misal: Lainnya, sebutkan)
            $table->string('input_type')->nullable(); // type input tambahan: text, textarea, number
            $table->string('trigger_question')->nullable(); // kode pertanyaan yang akan muncul jika opsi ini dipilih
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi_jawaban');
    }
};
