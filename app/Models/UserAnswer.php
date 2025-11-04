<?php

namespace App\Models;

use App\Livewire\Alumni;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi Inverse One-to-Many: Jawaban ini adalah untuk satu Pertanyaan.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    public function option()
    {
        return $this->belongsTo(Option::class, 'question_option_id');
    }

    public function alumnui(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumnus_id');
    }
}
