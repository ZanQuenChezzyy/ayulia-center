<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'nama',
        'email',
        'no_telepon',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pendidikan_terakhir',
        'ktp_url',
        'avatar_url',
        'bukti_pembayaran',
        'status_pembayaran',
        'status_pendaftaran',
    ];

    public function Kelas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(\App\Models\Kelas::class, 'kelas_id', 'id');
}

}