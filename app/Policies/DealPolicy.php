<?php

namespace App\Policies;

use App\Models\Deal;
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
     * @param  \App\Models\Deal  $deal
     * @return mixed
     */
    public function view(User $user, Deal $deal)
    {
        return true; // ? Va bene?
    }

    public function viewTrashed(User $user, Deal $deal)
    {
        return in_array($user->ruolo, [ 'admin' , 'account_manager' ] );
    }

    /**
     * Determine whether the user can create deals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array( $user->ruolo , [ 'admin' , 'account_manager' ] ) ;
    }

    /**
     * Determine whether the user can update the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Deal  $deal
     * @return mixed
     */
    public function update(User $user, Deal $deal)
    {
        return in_array( $user->ruolo , ['account_manager' , 'admin' ] );
    }

    /**
     * Determine whether the user can delete the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Deal  $deal
     * @return mixed
     */
    public function delete(User $user, Deal $deal)
    {
        return in_array( $user->ruolo , ['account_manager' , 'admin' ] );
    }

    /**
     * Determine whether the user can restore the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Deal  $deal
     * @return mixed
     */
    public function restore(User $user)
    {
        return in_array( $user->ruolo , ['account_manager' , 'admin' ] );
    }

    /**
     * Determine whether the user can permanently delete the deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Deal  $deal
     * @return mixed
     */
    public function forceDelete(User $user, Deal $deal)
    {
        return in_array( $user->ruolo , [ 'admin' ] );
    }
}
