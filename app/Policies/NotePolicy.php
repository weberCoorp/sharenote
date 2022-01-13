<?php

namespace App\Policies;

use App\Note;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function viewAny (User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\Note $note
     * @return mixed
     */
    public function view (?User $user, Note $note): bool
    {
        $valid = false;

        if ($note->private === 0) {
            $valid = true;
        } else {
            if ($user) {
                $usersHasAccessIds = $note->usersHasAccess->count () > 0 ? $note->usersHasAccess->pluck ('id')->toArray () : [];
                if (($user->id == $note->user_id) || ($user && in_array ($user->id, $usersHasAccessIds))) {
                    $valid = true;
                }
            }


        }
        return $valid;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create (User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\Note $note
     * @return mixed
     */
    public function update (User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\Note $note
     * @return mixed
     */
    public function delete (User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\User $user
     * @param \App\Note $note
     * @return mixed
     */
    public function restore (User $user, Note $note)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\User $user
     * @param \App\Note $note
     * @return mixed
     */
    public function forceDelete (User $user, Note $note)
    {
        //
    }
}
