<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserMeta;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @return void
     */
    public function creating(User $user)
    {
        $user->username = $user->generateUsername();
    }

    /**
     * Handle the User "created" event.
     *
     * @return void
     */
    public function created(User $user)
    {
        $user->profile()->create([
            'user_id' => $user->id,
        ]);

        UserMeta::create([
            'metaable_id' => $user->id,
            'metaable_type' => get_class($user),
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
