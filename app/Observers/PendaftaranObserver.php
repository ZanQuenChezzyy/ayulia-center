<?php

namespace App\Observers;

use App\Models\KelasUser;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PendaftaranObserver
{
    /**
     * Handle the Pendaftaran "created" event.
     */
    public function created(Pendaftaran $pendaftaran): void
    {
        // Cek status pendaftaran dan pembayaran
        if ($pendaftaran->status_pendaftaran == 1 && $pendaftaran->status_pembayaran == 1) {
            // Format password berdasarkan tanggal lahir
            $password = Carbon::parse($pendaftaran->tanggal_lahir)->format('dmY');

            // Buat user baru jika belum ada
            $user = User::firstOrCreate(
                ['email' => $pendaftaran->email], // Cek berdasarkan email
                [
                    'name' => $pendaftaran->nama,
                    'email' => $pendaftaran->email,
                    'no_telepon' => $pendaftaran->no_telepon,
                    'tempat_lahir' => $pendaftaran->tempat_lahir,
                    'tanggal_lahir' => $pendaftaran->tanggal_lahir,
                    'pendidikan_terakhir' => $pendaftaran->pendidikan_terakhir,
                    'ktp_url' => $pendaftaran->ktp_url,
                    'avatar_url' => $pendaftaran->avatar_url,
                    'password' => Hash::make($password), // Buat password dari tanggal lahir
                ]
            );
            $user->assignRole('Peserta');

            // Hubungkan user ke kelas
            KelasUser::firstOrCreate(
                ['user_id' => $user->id, 'kelas_id' => $pendaftaran->kelas_id],
                []
            );
        }
    }

    /**
     * Handle the Pendaftaran "updated" event.
     */
    public function updated(Pendaftaran $pendaftaran): void
    {
        // Cek status pendaftaran dan pembayaran
        if ($pendaftaran->status_pendaftaran == 1 && $pendaftaran->status_pembayaran == 1) {
            // Format password berdasarkan tanggal lahir
            $password = Carbon::parse($pendaftaran->tanggal_lahir)->format('dmY');

            // Buat user baru jika belum ada
            $user = User::firstOrCreate(
                ['email' => $pendaftaran->email], // Cek berdasarkan email
                [
                    'name' => $pendaftaran->nama,
                    'email' => $pendaftaran->email,
                    'no_telepon' => $pendaftaran->no_telepon,
                    'tempat_lahir' => $pendaftaran->tempat_lahir,
                    'tanggal_lahir' => $pendaftaran->tanggal_lahir,
                    'pendidikan_terakhir' => $pendaftaran->pendidikan_terakhir,
                    'ktp_url' => $pendaftaran->ktp_url,
                    'avatar_url' => $pendaftaran->avatar_url,
                    'password' => Hash::make($password), // Buat password dari tanggal lahir
                ]
            );
            $user->assignRole('Peserta');

            // Hubungkan user ke kelas
            KelasUser::firstOrCreate(
                ['user_id' => $user->id, 'kelas_id' => $pendaftaran->kelas_id],
                []
            );
        }
    }

    /**
     * Handle the Pendaftaran "deleted" event.
     */
    public function deleted(Pendaftaran $pendaftaran): void
    {
        //
    }

    /**
     * Handle the Pendaftaran "restored" event.
     */
    public function restored(Pendaftaran $pendaftaran): void
    {
        //
    }

    /**
     * Handle the Pendaftaran "force deleted" event.
     */
    public function forceDeleted(Pendaftaran $pendaftaran): void
    {
        //
    }
}
