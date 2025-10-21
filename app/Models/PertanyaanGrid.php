<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PertanyaanGrid extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_grid';

    protected $fillable = [
        'pertanyaan_id',
        'row_label',
        'kode_row',
        'column_group',
        'urutan',
    ];

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class);
    }
}
