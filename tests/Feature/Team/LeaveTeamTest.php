<?php

namespace Tests\Feature\Team;

use App\Models\User;
use Tests\Feature\AuthenticatedTestCase;

class LeaveTeamTest extends AuthenticatedTestCase
{
    public function test_users_can_leave_teams()
    {
        $this->user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['permissions' => ['server:create']]
        );

        $this->actingAs($otherUser);

        $this->delete('/teams/' . $this->user->currentTeam->id . '/members/' . $otherUser->id);

        $this->assertCount(0, $this->user->currentTeam->fresh()->users);
    }

    public function test_team_owners_cant_leave_their_own_team()
    {
        $response = $this->delete('/teams/' . $this->user->currentTeam->id . '/members/' . $this->user->id);

        $response->assertSessionHasErrorsIn('removeTeamMember', ['team']);

        $this->assertNotNull($this->user->currentTeam->fresh());
    }
}
