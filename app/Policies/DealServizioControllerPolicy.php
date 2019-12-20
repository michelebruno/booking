<?php

namespace App\Policies;

use App\Http\Controllers\API\DealServizioController;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealServizioControllerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any deal servizio controllers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the deal servizio controller.
     *
     * @param  \App\User  $user
     * @param  \App\Http\Controllers\API\DealServizioController  $dealServizioController
     * @return mixed
     */
    public function view(User $user, DealServizioController $dealServizioController)
    {
        //
    }

    /**
     * Determine whether the user can create deal servizio controllers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the deal servizio controller.
     *
     * @param  \App\User  $user
     * @param  \App\Http\Controllers\API\DealServizioController  $dealServizioController
     * @return mixed
     */
    public function update(User $user, DealServizioController $dealServizioController)
    {
        //
    }

    /**
     * Determine whether the user can delete the deal servizio controller.
     *
     * @param  \App\User  $user
     * @param  \App\Http\Controllers\API\DealServizioController  $dealServizioController
     * @return mixed
     */
    public function delete(User $user, DealServizioController $dealServizioController)
    {
        //
    }

    /**
     * Determine whether the user can restore the deal servizio controller.
     *
     * @param  \App\User  $user
     * @param  \App\Http\Controllers\API\DealServizioController  $dealServizioController
     * @return mixed
     */
    public function restore(User $user, DealServizioController $dealServizioController)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the deal servizio controller.
     *
     * @param  \App\User  $user
     * @param  \App\Http\Controllers\API\DealServizioController  $dealServizioController
     * @return mixed
     */
    public function forceDelete(User $user, DealServizioController $dealServizioController)
    {
        //
    }
}
