<?php

namespace App\Policies;

use App\Fornitore;
use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class FornitorePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any fornitores.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT ] ) ;
    }

    /**
     * Determine whether the user can view the fornitore.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitore  $fornitore
     * @return mixed
     */
    public function view(User $user, Fornitore $fornitore)
    {
        return $user->id == $fornitore->id || in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT ] ) ;
    }

    /**
     * Determine whether the user can create fornitores.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT ] ) ;
    }

    /**
     * Determine whether the user can update the fornitore.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitore  $fornitore
     * @return mixed
     */
    public function update(User $user, Fornitore $fornitore)
    {
        return in_array($user->ruolo, [User::RUOLO_ADMIN, User::RUOLO_ACCOUNT ]) || $user->id == $fornitore->id;
    }

    /**
     * Determine whether the user can delete the fornitore.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitore  $fornitore
     * @return mixed
     */
    public function delete(User $user, Fornitore $fornitore)
    {
        return in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT ] ) ;
    }

    /**
     * Determine whether the user can restore the fornitore.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitore  $fornitore
     * @return mixed
     */
    public function restore(User $user, Fornitore $fornitore)
    {
        return in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT ] ) ;
    }

    /**
     * Determine whether the user can permanently delete the fornitore.
     *
     * @param  \App\User  $user
     * @param  \App\Fornitore  $fornitore
     * @return mixed
     */
    public function forceDelete(User $user, Fornitore $fornitore)
    {
        return in_array( $user->ruolo , [ User::RUOLO_ADMIN ] ) ;
    }
}
