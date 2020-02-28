<?php

namespace App\Policies;

use App\Fornitura;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FornituraPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any fornituras.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the fornitura.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitura  $fornitura
     * @return mixed
     */
    public function view(User $user, Fornitura $fornitura)
    {
        return $fornitura->fornitura_id == $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create fornituras.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the fornitura.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitura  $fornitura
     * @return mixed
     */
    public function update(User $user, Fornitura $fornitura)
    {
        return $user->isAdmin(); // ? L'fornitura può fare qualcosa?
    }

    /**
     * Determine whether the user can delete the fornitura.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitura  $fornitura
     * @return mixed
     */
    public function delete(User $user, Fornitura $fornitura)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the fornitura.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitura  $fornitura
     * @return mixed
     */
    public function restore(User $user, Fornitura $fornitura)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the fornitura.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitura  $fornitura
     * @return mixed
     */
    public function forceDelete(User $user, Fornitura $fornitura)
    {
        return $user->isAdmin();
    }
}
