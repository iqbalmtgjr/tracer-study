<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'kode_pt',
        'kode_prodi',
        'nim',
        'nik',
        'npwp',
        'nama_lengkap',
        'tanggal_lahir',
        'no_hp',
        'email',
        'tahun_lulus',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function responden(): HasMany
    {
        return $this->hasMany(Responden::class);
    }

    public function riwayatPekerjaan(): HasMany
    {
        return $this->hasMany(RiwayatPekerjaan::class);
    }

    public function riwayatPendidikan(): HasMany
    {
        return $this->hasMany(RiwayatPendidikan::class);
    }
}
