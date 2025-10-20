<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawaban';

    protected $fillable = [
        'responden_id',
        'pertanyaan_id',
        'opsi_jawaban_id',
        'jawaban_text',
    ];

    public function responden(): BelongsTo
    {
        return $this->belongsTo(Responden::class);
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class);
    }

    public function opsiJawaban(): BelongsTo
    {
        return $this->belongsTo(OpsiJawaban::class);
    }
}
