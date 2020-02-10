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
        //
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
        //
    }

    /**
     * Determine whether the user can create ordines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
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
        //
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

    /**
     * Determine whether the user can restore the ordine.
     *
     * @param  \App\User  $user
     * @param  \App\Ordine  $ordine
     * @return mixed
     */
    public function restore(User $user, Ordine $ordine)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the ordine.
     *
     * @param  \App\User  $user
     * @param  \App\Ordine  $ordine
     * @return mixed
     */
    public function forceDelete(User $user, Ordine $ordine)
    {
        //
    }
}