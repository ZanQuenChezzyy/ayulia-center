<?php

namespace App\Policies;

use App\Models\Pesan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PesanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat QNA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pesan $pesan): bool
    {
        return $user->can('Lihat QNA');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah QNA');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pesan $pesan): bool
    {
        return $user->can('Ubah QNA');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pesan $pesan): bool
    {
        return $user->can('Hapus QNA');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pesan $pesan): bool
    {
        return $user->can('Hapus QNA');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pesan $pesan): bool
    {
        return $user->can('Hapus QNA');
    }
}