<?php

namespace App\Policies;

use App\Models\Tariffa;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TariffaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tariffas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Tariffa  $tariffa
     * @return mixed
     */
    public function view(User $user, Tariffa $tariffa)
    {
        //
    }

    /**
     * Determine whether the user can create tariffas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Tariffa  $tariffa
     * @return mixed
     */
    public function update(User $user, Tariffa $tariffa)
    {
        return in_array($user->ruolo, [ 'admin' , 'account_manager' ]);
    }

    /**
     * Determine whether the user can delete the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Tariffa  $tariffa
     * @return mixed
     */
    public function delete(User $user, Tariffa $tariffa)
    {
        if ( $tariffa->slug == "intero") {
            return false;
        } else return true;
    }

    /**
     * Determine whether the user can restore the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Tariffa  $tariffa
     * @return mixed
     */
    public function restore(User $user, Tariffa $tariffa)
    {
        // non usa softdeletes
    }

    /**
     * Determine whether the user can permanently delete the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Tariffa  $tariffa
     * @return mixed
     */
    public function forceDelete(User $user, Tariffa $tariffa)
    {
        // non usa softdeletes
    }
}
