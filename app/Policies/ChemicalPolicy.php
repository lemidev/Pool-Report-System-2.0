<?php

namespace App\Policies;

use App\User;
use App\Chemical;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChemicalPolicy
{
    use HandlesAuthorization;

    /**
     * Administrator has all permissions
     */
    public function before(User $user)
    {
        if($user->selectedUser->isRole('admin')){
            return true;
        }
    }

    public function list(User $user)
    {
        return $user->selectedUser->hasPermission('chemical', 'view');
    }

    /**
     * Determine whether the user can view the chemical.
     *
     * @param  \App\User  $user
     * @param  \App\Chemical  $chemical
     * @return mixed
     */
    public function view(User $user, Chemical $chemical)
    {
        return $user->selectedUser->hasPermission('chemical', 'view');
    }

    /**
     * Determine whether the user can create chemicals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->selectedUser->hasPermission('chemical', 'create');
    }

    /**
     * Determine whether the user can update the chemical.
     *
     * @param  \App\User  $user
     * @param  \App\Chemical  $chemical
     * @return mixed
     */
    public function update(User $user, Chemical $chemical)
    {
        return $user->selectedUser->hasPermission('chemical', 'update');
    }

    /**
     * Determine whether the user can delete the chemical.
     *
     * @param  \App\User  $user
     * @param  \App\Chemical  $chemical
     * @return mixed
     */
    public function delete(User $user, Chemical $chemical)
    {
        return $user->selectedUser->hasPermission('chemical', 'delete');
    }
}
