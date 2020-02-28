<?php

namespace App\Policies;

use App\Deal; 
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any deals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Deal  $deal
     * @return mixed
     */
    public function view(User $user, Deal $deal)
    {     
        if ( $deal->trashed() ) {
            return in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT] );
        } else return true;

    }

    /**
     * Determine whether the user can create deals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array( $user->ruolo , [ User::RUOLO_ADMIN , User::RUOLO_ACCOUNT] );
    }

    /**
     * Determine whether the user can update the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Deal  $deal
     * @return mixed
     */
    public function update(User $user, Deal $deal)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Deal  $deal
     * @return mixed
     */
    public function delete(User $user, Deal $deal)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Deal  $deal
     * @return mixed
     */
    public function restore(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Deal  $deal
     * @return mixed
     */
    public function forceDelete(User $user, Deal $deal)
    {
        return $user->isSuperAdmin();
    }

    /**
     * * FORNITURE COLLEGATE
     */

    public function createFornitura(User $user, Deal $deal)
    {        
        return $user->isSuperAdmin();
    }

    public function updateFornitura(User $user, Deal $deal)
    {        
        return $user->isSuperAdmin();
    }

    public function deleteFornitura(User $user, Deal $deal)
    {        
        return $user->isSuperAdmin();
    }
}
