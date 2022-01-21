<?php

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class AddTeamMember
{
    /**
     * Add a new team member to the given team.
     *
     * @param mixed $user
     * @param mixed $team
     * @param string $email
     * @param array $permissions
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add(User $user, Team $team, string $email, array $permissions = [])
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $permissions);

        $newTeamMember = User::query()->where('email', $email)->firstOrFail();

        $team->users()->attach(
            $newTeamMember, ['permissions' => $permissions]
        );
    }

    /**
     * Validate the add member operation.
     *
     * @param mixed $team
     * @param string $email
     * @param array|null $permissions
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate($team, string $email, ?array $permissions)
    {
        Validator::make([
            'email' => $email,
            'permissions' => $permissions,
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'permissions' => [
                'required',
            ],
            'permissions.*' => [
                'in:' . implode(',', config('core.permissions'))
            ]
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     *
     * @param Team $team
     * @param string $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, string $email)
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
