<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioner')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('pertanyaan')->onDelete('cascade'); // untuk sub-pertanyaan
            $table->string('kode_pertanyaan')->nullable(); // kode seperti f8, f502, f1101
            $table->text('pertanyaan');
            $table->enum('tipe_pertanyaan', [
                'text',
                'textarea',
                'radio',
                'checkbox',
                'select',
                'date',
                'number',
                'month', // untuk input bulan
                'grid' // untuk pertanyaan matrix seperti kompetensi
            ]);
            $table->boolean('is_required')->default(false);
            $table->boolean('allow_multiple')->default(false); // untuk checkbox multiple
            $table->integer('urutan')->default(0);
            $table->json('kondisi_tampil')->nullable(); // untuk conditional logic
            $table->text('keterangan')->nullable(); // keterangan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};
