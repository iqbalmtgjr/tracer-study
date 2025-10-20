<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected $fillable = [
        'kuesioner_id',
        'pertanyaan',
        'tipe_pertanyaan',
        'is_required',
        'urutan',
        'kondisi_tampil',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'urutan' => 'integer',
    ];

    public function kuesioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function opsiJawaban(): HasMany
    {
        return $this->hasMany(OpsiJawaban::class)->orderBy('urutan');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }
}
