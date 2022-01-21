<?php

namespace App\Actions\Team;

use App\Mail\TeamInvitation;
use App\Models\Team;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InviteTeamMember
{
    /**
     * Invite a new team member to the given team.
     *
     * @param mixed $team
     * @param string $email
     * @param array $permissions
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function invite(Team $team, string $email, array $permissions = [])
    {
        $this->validate($team, $email, $permissions);

        $invitation = $team->teamInvitations()->create([
            'email' => $email,
            'permissions' => $permissions,
        ]);

        Mail::to($email)->send(new TeamInvitation($invitation));
    }

    /**
     * Validate the invite member operation.
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
        ], $this->rules($team), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @param mixed $team
     * @return array
     */
    protected function rules($team)
    {
        return array_filter([
            'email' => ['required', 'email', Rule::unique('team_invitations')->where(function ($query) use ($team) {
                $query->where('team_id', $team->id);
            })],
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
     * @param mixed $team
     * @param string $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam($team, string $email)
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
