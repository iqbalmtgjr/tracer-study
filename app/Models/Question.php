<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quisioner_id',
        'question_code',
        'text',
        'input_type',
        'conditional_parent_code',
        'conditional_parent_value'
    ];

    /**
     * Relasi One-to-Many: Sebuah Pertanyaan memiliki banyak Pilihan Jawaban.
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Relasi One-to-Many: Sebuah Pertanyaan dapat dijawab banyak kali (oleh banyak alumni).
     */
    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
    public function quisioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class, 'quisioner_id');
    }
}
