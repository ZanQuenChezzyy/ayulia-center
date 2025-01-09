<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'instruktur_id',
        'nama',
        'tingkatan',
        'jumlah_pertemuan',
        'jam_mulai',
        'jam_selesai',
        'harga',
        'deskripsi',
    ];

    public function Instruktur(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Instruktur::class, 'instruktur_id', 'id');
    }

    public function pendaftarans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Pendaftaran::class);
    }

    public function kelasUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\KelasUser::class);
    }
}
