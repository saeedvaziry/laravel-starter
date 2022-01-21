<?php

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateTeamMember
{
    /**
     * Invite a new team member to the given team.
     *
     * @param mixed $team
     * @param $userId
     * @param array $permissions
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Team $team, $userId, array $permissions = [])
    {
        $user = User::query()->findOrFail($userId);

        if (!$team->hasUser($user)) {
            abort(403);
        }

        $this->validate($permissions);

        $team->users()->updateExistingPivot($user, [
            'permissions' => $permissions
        ]);
    }

    /**
     * Validate the invite member operation.
     *
     * @param array|null $permissions
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(?array $permissions)
    {
        Validator::make([
            'permissions' => $permissions,
        ], $this->rules())->validateWithBag('updateTeamMember');
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'permissions' => [
                'required',
            ],
            'permissions.*' => [
                'in:' . implode(',', config('core.permissions'))
            ]
        ]);
    }
}
