<?php

namespace App\Actions\Team;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateTeam
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param mixed $user
     * @param array $input
     * @return mixed
     * @throws \Exception
     */
    public function create(User $user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        $user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => false,
        ]));

        return $team;
    }
}
