<?php

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class RemoveTeamMember
{
    /**
     * Remove the team member from the given team.
     *
     * @param mixed $user
     * @param mixed $team
     * @param mixed $teamMember
     * @return void
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function remove(User $user, Team $team, $teamMember)
    {
        $this->authorize($user, $team, $teamMember);

        $this->ensureUserDoesNotOwnTeam($teamMember, $team);

        $team->removeUser($teamMember);
    }

    /**
     * Authorize that the user can remove the team member.
     *
     * @param mixed $user
     * @param mixed $team
     * @param mixed $teamMember
     * @return void
     * @throws AuthorizationException
     */
    protected function authorize($user, $team, $teamMember)
    {
        if (!Gate::forUser($user)->check('removeTeamMember', $team) &&
            $user->id !== $teamMember->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the team.
     *
     * @param mixed $teamMember
     * @param mixed $team
     * @return void
     * @throws ValidationException
     */
    protected function ensureUserDoesNotOwnTeam($teamMember, $team)
    {
        if ($teamMember->id === $team->owner->id) {
            throw ValidationException::withMessages([
                'team' => [__('You may not leave a team that you created.')],
            ])->errorBag('removeTeamMember');
        }
    }
}
