<?php

namespace App\Policies;

use App\Models\Instruktur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InstrukturPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat Instruktur');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Instruktur $instruktur): bool
    {
        return $user->can('Lihat Instruktur');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Instruktur');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Instruktur $instruktur): bool
    {
        return $user->can('Ubah Instruktur');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Instruktur $instruktur): bool
    {
        return $user->can('Hapus Instruktur');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Instruktur $instruktur): bool
    {
        return $user->can('Hapus Instruktur');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Instruktur $instruktur): bool
    {
        return $user->can('Hapus Instruktur');
    }
}
