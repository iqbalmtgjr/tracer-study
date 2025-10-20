<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pekerjaan';

    protected $fillable = [
        'alumni_id',
        'nama_perusahaan',
        'posisi',
        'jenis_pekerjaan',
        'gaji',
        'tanggal_mulai',
        'tanggal_selesai',
        'masih_bekerja',
        'deskripsi_pekerjaan',
        'kota',
    ];

    protected $casts = [
        'gaji' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'masih_bekerja' => 'boolean',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }
}
