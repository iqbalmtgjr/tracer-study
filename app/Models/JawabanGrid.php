<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanGrid extends Model
{
    use HasFactory;

    protected $table = 'jawaban_grid';

    protected $fillable = [
        'responden_id',
        'pertanyaan_id',
        'pertanyaan_grid_id',
        'column_group',
        'nilai',
    ];

    public function responden(): BelongsTo
    {
        return $this->belongsTo(Responden::class);
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class);
    }

    public function pertanyaanGrid(): BelongsTo
    {
        return $this->belongsTo(PertanyaanGrid::class);
    }
}
