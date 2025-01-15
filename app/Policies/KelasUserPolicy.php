<?php

namespace App\Policies;

use App\Models\KelasUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KelasUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat Kelas Peserta');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KelasUser $kelasUser): bool
    {
        return $user->can('Lihat Kelas Peserta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Kelas Peserta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KelasUser $kelasUser): bool
    {
        return $user->can('Ubah Kelas Peserta');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KelasUser $kelasUser): bool
    {
        return $user->can('Hapus Kelas Peserta');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KelasUser $kelasUser): bool
    {
        return $user->can('Hapus Kelas Peserta');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KelasUser $kelasUser): bool
    {
        return $user->can('Hapus Kelas Peserta');
    }
}
