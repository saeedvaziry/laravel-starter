<?php

namespace App\Actions\Team;

use App\Models\Team;
use Illuminate\Support\Facades\Validator;

class UpdateTeamName
{
    /**
     * Validate and update the given team's name.
     *
     * @param mixed $team
     * @param array $input
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Team $team, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('updateTeamName');

        $team->forceFill([
            'name' => $input['name'],
        ])->save();
    }
}
