<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteUser
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->cleanDelete();
        });
    }
}
