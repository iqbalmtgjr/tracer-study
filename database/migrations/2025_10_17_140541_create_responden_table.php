<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioner')->onDelete('cascade');
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            // Alumni hanya bisa mengisi kuesioner yang sama sekali
            $table->unique(['kuesioner_id', 'alumni_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responden');
    }
};
