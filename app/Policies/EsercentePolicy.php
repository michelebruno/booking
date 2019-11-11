<?php

namespace App\Policies;

use App\Models\Esercente;
use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EsercentePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any esercentes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return in_array( $user->ruolo , [ 'admin' , 'account_manager' ] ) ;
    }

    /**
     * Determine whether the user can view the esercente.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Esercente  $esercente
     * @return mixed
     */
    public function view(User $user, Esercente $esercente)
    {
        return $user->id === $esercente->id || in_array( $user->ruolo , [ 'admin' , 'account_manager' ] ) ;
    }

    /**
     * Determine whether the user can create esercentes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array( $user->ruolo , [ 'admin' , 'account_manager' ] ) ;
    }

    /**
     * Determine whether the user can update the esercente.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Esercente  $esercente
     * @return mixed
     */
    public function update(User $user, Esercente $esercente)
    {
        //
    }

    /**
     * Determine whether the user can delete the esercente.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Esercente  $esercente
     * @return mixed
     */
    public function delete(User $user, Esercente $esercente)
    {
        //
    }

    /**
     * Determine whether the user can restore the esercente.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Esercente  $esercente
     * @return mixed
     */
    public function restore(User $user, Esercente $esercente)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the esercente.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Esercente  $esercente
     * @return mixed
     */
    public function forceDelete(User $user, Esercente $esercente)
    {
        //
    }
}