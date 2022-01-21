<?php

namespace App\Http\Controllers;

use App\Actions\Team\AddTeamMember;
use App\Actions\Team\InviteTeamMember;
use App\Actions\Team\RemoveTeamMember;
use App\Actions\Team\UpdateTeamMember;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Add a new team member to a team.
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Team $team)
    {
        $this->authorize('addTeamMember', $team);

        app(InviteTeamMember::class)->invite(
            $team,
            $request->email ?: '',
            $request->permissions
        );

        return back(303);
    }

    /**
     * Update the given team member's role.
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Team $team, int $userId)
    {
        $this->authorize('updateTeamMember', $team);

        app(UpdateTeamMember::class)->update(
            $team,
            $userId,
            $request->permissions
        );

        return back(303);
    }

    /**
     * Remove the given user from the given team.
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Team $team, $userId)
    {
        app(RemoveTeamMember::class)->remove(
            $request->user(),
            $team,
            $user = User::query()->findOrFail($userId)
        );

        if ($request->user()->id === $user->id) {
            return redirect(config('fortify.home'));
        }

        return back(303);
    }

    /**
     * Accept a team invitation.
     *
     * @param \Illuminate\Http\Request $request
     * @param TeamInvitation $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptInvitation(Request $request, TeamInvitation $invitation)
    {
        app(AddTeamMember::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->email,
            $invitation->permissions
        );

        $invitation->delete();

        $request->session()->flash(
            'flash.banner',
            __('Great! You have accepted the invitation to join the :team team.', [
                'team' => $invitation->team->name
            ])
        );
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Cancel the given team invitation.
     *
     * @param TeamInvitation $invitation
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function destroyInvitation(TeamInvitation $invitation)
    {
        $this->authorize('removeTeamMember', $invitation->team);

        $invitation->delete();

        return back(303);
    }
}
