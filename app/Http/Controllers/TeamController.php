<?php

namespace App\Http\Controllers;

use App\Actions\Team\CreateTeam;
use App\Actions\Team\DeleteTeam;
use App\Actions\Team\UpdateTeamName;
use App\Models\Team;
use App\Providers\RouteServiceProvider;
use App\Traits\RedirectsActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TeamController extends Controller
{
    use RedirectsActions;

    /**
     * Show the team management screen.
     *
     * @param int $teamId
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $teamId)
    {
        $team = Team::query()->findOrFail($teamId);

        $this->authorize('view', $team);

        return Inertia::render('Teams/Show', [
            'team' => $team->load('owner', 'users', 'teamInvitations'),
            'availablePermissions' => config('core.permissions'),
            'defaultPermissions' => config('core.permissions'),
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', [$team, false]),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }

    /**
     * Show the team creation screen.
     *
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Team::class);

        return Inertia::render('Teams/Create');
    }

    /**
     * Create a new team.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Team::class);

        $creator = app(CreateTeam::class);

        $creator->create($request->user(), $request->all());

        return $this->redirectPath($creator);
    }

    /**
     * Update the given team's name.
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        app(UpdateTeamName::class)->update($team, $request->all());

        return back(303);
    }

    /**
     * Delete the given team.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        if ($team->personal_team) {
            throw ValidationException::withMessages([
                'team' => __('You may not delete your personal team.'),
            ])->errorBag('deleteTeam');
        }

        $deleter = app(DeleteTeam::class);

        $deleter->delete($team);

        return $this->redirectPath($deleter);
    }

    /**
     * Update the authenticated user's current team.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCurrentTeam(Request $request)
    {
        $team = Team::query()->findOrFail($request->team_id);

        if (!$request->user()->switchTeam($team)) {
            abort(403);
        }

        return redirect(RouteServiceProvider::HOME, 303);
    }
}
