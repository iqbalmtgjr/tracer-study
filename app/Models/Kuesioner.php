<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kuesioner extends Model
{
    use HasFactory;

    protected $table = 'kuesioner';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function pertanyaan(): HasMany
    {
        return $this->hasMany(Pertanyaan::class)->orderBy('urutan');
    }

    public function responden(): HasMany
    {
        return $this->hasMany(Responden::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }
}
