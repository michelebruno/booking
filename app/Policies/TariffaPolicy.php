<?php

namespace App\Policies;

use App\Deal;
use App\Fornitura;
use App\Tariffa;
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
    public function viewAny(User $user, ?Deal $deal, ?Fornitura $fornitura )
    {
        //
    }

    /**
     * Determine whether the user can view the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Tariffa  $tariffa
     * @return mixed
     */
    public function view(User $user, Tariffa $tariffa, ?Deal $deal, ?Fornitura $fornitura )
    {
        //
    }

    /**
     * Determine whether the user can create tariffas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, ?Deal $deal, ?Fornitura $fornitura )
    {
        //
    }

    /**
     * Determine whether the user can update the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Tariffa  $tariffa
     * @return mixed
     */
    public function update(User $user, Tariffa $tariffa, ?Deal $deal, ?Fornitura $fornitura )
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the tariffa.
     *
     * @param  \App\User  $user
     * @param  \App\Tariffa  $tariffa
     * @return mixed
     */
    public function delete(User $user, Tariffa $tariffa, ?Deal $deal, ?Fornitura $fornitura)
    {
        if ( $tariffa->slug == "intero") {
            return false;
        } 
        

        return $user->isAdmin();
        
    }
}
