<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GridRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'row_code',
        'row_label'
    ];

    // Nonaktifkan timestamps jika Anda tidak ingin kolom created_at dan updated_at
    // public $timestamps = false;

    /**
     * Relasi Inverse One-to-Many: Baris ini milik satu Pertanyaan (Question).
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
