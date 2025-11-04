<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';
    protected $primaryKey = 'kode_kabupaten_kota';

    public $timestamps = false;

    protected $fillable = [
        'kode_provinsi',
        'kode_kabupaten_kota',
        'nama_kabupaten_kota',
    ];
}
