<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto',
        'nama',
        'no_telepon',
        'pendidikan_terakhir',
        'sertifikat',
        'pengalaman',
        'di_tampilkan',
    ];

    public function kelas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Kelas::class);
    }
}
