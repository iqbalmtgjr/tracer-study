<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responden_id')->constrained('responden')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->onDelete('cascade');
            $table->foreignId('opsi_jawaban_id')->nullable()->constrained('opsi_jawaban')->onDelete('cascade');
            $table->text('jawaban_text')->nullable(); // untuk text/textarea
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};
