<?php // TODO tutto

namespace App\Policies;

use App\Models\TariffaDeal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TariffaDealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tariffa deals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the tariffa deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\TariffaDeal  $tariffaDeal
     * @return mixed
     */
    public function view(User $user, TariffaDeal $tariffaDeal)
    {
        return true; // TODO
    }

    /**
     * Determine whether the user can create tariffa deals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the tariffa deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\TariffaDeal  $tariffaDeal
     * @return mixed
     */
    public function update(User $user, TariffaDeal $tariffaDeal)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the tariffa deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\TariffaDeal  $tariffaDeal
     * @return mixed
     */
    public function delete(User $user, TariffaDeal $tariffaDeal)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the tariffa deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\TariffaDeal  $tariffaDeal
     * @return mixed
     */
    public function restore(User $user, TariffaDeal $tariffaDeal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the tariffa deal.
     *
     * @param  \App\User  $user
     * @param  \App\Models\TariffaDeal  $tariffaDeal
     * @return mixed
     */
    public function forceDelete(User $user, TariffaDeal $tariffaDeal)
    {
        //
    }
}
