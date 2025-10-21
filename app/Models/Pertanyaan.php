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
        'parent_id',
        'kode_pertanyaan',
        'pertanyaan',
        'tipe_pertanyaan',
        'is_required',
        'allow_multiple',
        'urutan',
        'kondisi_tampil',
        'keterangan',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'allow_multiple' => 'boolean',
        'urutan' => 'integer',
        'kondisi_tampil' => 'array',
    ];

    public function kuesioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'parent_id');
    }

    public function opsiJawaban(): HasMany
    {
        return $this->hasMany(OpsiJawaban::class)->orderBy('urutan');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }

    public function pertanyaanGrid(): HasMany
    {
        return $this->hasMany(PertanyaanGrid::class);
    }
}
