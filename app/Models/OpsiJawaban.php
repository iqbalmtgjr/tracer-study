<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpsiJawaban extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'pertanyaan_id',
        'opsi',
        'nilai',
        'urutan',
    ];

    protected $casts = [
        'nilai' => 'integer',
        'urutan' => 'integer',
    ];

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class);
    }
}
