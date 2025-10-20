<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPendidikan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pendidikan';

    protected $fillable = [
        'alumni_id',
        'nama_institusi',
        'jenjang',
        'program_studi',
        'tanggal_mulai',
        'tanggal_selesai',
        'masih_studi',
        'ipk',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'masih_studi' => 'boolean',
        'ipk' => 'decimal:2',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }
}
