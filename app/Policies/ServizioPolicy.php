<?php

namespace App\Policies;

use App\Models\Servizio;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServizioPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any servizios.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return in_array($user->ruolo, [ 'admin', 'account_manager' ]);
    }

    /**
     * Determine whether the user can view the servizio.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Servizio  $servizio
     * @return mixed
     */
    public function view(User $user, Servizio $servizio)
    {
        return $servizio->esercente_id == $user->id || in_array( $user->ruolo, [ 'admin', 'account_manager' ] );
    }

    /**
     * Determine whether the user can create servizios.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array($user->ruolo, [ 'admin', 'account_manager' ]);
    }

    /**
     * Determine whether the user can update the servizio.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Servizio  $servizio
     * @return mixed
     */
    public function update(User $user, Servizio $servizio)
    {
        return in_array($user->ruolo, [ 'admin', 'account_manager' ]);
    }

    /**
     * Determine whether the user can delete the servizio.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Servizio  $servizio
     * @return mixed
     */
    public function delete(User $user, Servizio $servizio)
    {
        return in_array($user->ruolo, [ 'admin', 'account_manager' ]);
    }

    /**
     * Determine whether the user can restore the servizio.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Servizio  $servizio
     * @return mixed
     */
    public function restore(User $user, Servizio $servizio)
    {
        return in_array($user->ruolo, [ 'admin', 'account_manager' ]);
    }

    /**
     * Determine whether the user can permanently delete the servizio.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Servizio  $servizio
     * @return mixed
     */
    public function forceDelete(User $user, Servizio $servizio)
    {
        return in_array($user->ruolo, [ 'admin', 'account_manager' ]);
    }
}
