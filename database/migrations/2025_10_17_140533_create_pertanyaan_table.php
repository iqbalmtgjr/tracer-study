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
            $table->text('pertanyaan');
            $table->enum('tipe_pertanyaan', [
                'text',
                'textarea',
                'radio',
                'checkbox',
                'select',
                'date',
                'number'
            ]);
            $table->boolean('is_required')->default(true);
            $table->integer('urutan')->default(0);
            $table->string('kondisi_tampil')->nullable(); // untuk conditional question
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};
