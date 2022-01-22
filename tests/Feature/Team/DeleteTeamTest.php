<?php

namespace Tests\Feature\Team;

use App\Models\Team;
use App\Models\User;
use Tests\Feature\AuthenticatedTestCase;

class DeleteTeamTest extends AuthenticatedTestCase
{
    public function test_teams_can_be_deleted()
    {
        $this->user->ownedTeams()->save($team = Team::factory()->make([
            'personal_team' => false,
        ]));

        $team->users()->attach(
            $otherUser = User::factory()->create(), ['permissions' => 'server:create']
        );

        $this->delete('/teams/' . $team->id);

        $this->assertNull($team->fresh());
        $this->assertCount(0, $otherUser->fresh()->teams);
    }

    public function test_personal_teams_cant_be_deleted()
    {
        $this->delete('/teams/' . $this->user->currentTeam->id);

        $this->assertNotNull($this->user->currentTeam->fresh());
    }
}
