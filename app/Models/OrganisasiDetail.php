<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_kordinator',
        'nim',
        'jabatan',
        'jumlah_mahasiswa'
    ];

    public function getMahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'id');
    }
}
