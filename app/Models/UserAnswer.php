<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumnus_id',
        'question_id',
        'answer_value',
        'question_code' // Disimpan untuk memudahkan query
    ];

    /**
     * Relasi Inverse One-to-Many: Jawaban ini adalah untuk satu Pertanyaan.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /*
    // Jika Anda memiliki Model Alumnus/User:
    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class);
    }
    */
}
