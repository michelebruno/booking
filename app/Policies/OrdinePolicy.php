<?php

namespace App\Policies;

use App\Ordine;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrdinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any ordines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the ordine.
     *
     * @param  \App\User  $user
     * @param  \App\Ordine  $ordine
     * @return mixed
     */
    public function view(User $user, Ordine $ordine)
    {
        return $user->isAdmin() || $user->id === $ordine->cliente_id;
    }

    /**
     * Determine whether the user can create ordines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the ordine.
     *
     * @param  \App\User  $user
     * @param  \App\Ordine  $ordine
     * @return mixed
     */
    public function update(User $user, Ordine $ordine)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the ordine.
     *
     * @param  \App\User  $user
     * @param  \App\Ordine  $ordine
     * @return mixed
     */
    public function delete(User $user, Ordine $ordine)
    {
        //
    }
}
