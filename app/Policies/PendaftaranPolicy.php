<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PendaftaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat Pendaftar');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->can('Lihat Pendaftar');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Pendaftar');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->can('Ubah Pendaftar');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->can('Hapus Pendaftar');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->can('Hapus Pendaftar');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->can('Hapus Pendaftar');
    }
}
