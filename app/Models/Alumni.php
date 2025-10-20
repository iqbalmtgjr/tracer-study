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
        'user_id',
        'program_studi_id',
        'nim',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'email',
        'alamat',
        'tahun_lulus',
        'ipk',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'ipk' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

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
