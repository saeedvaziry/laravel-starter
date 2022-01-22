<?php

namespace Tests\Feature\Team;

use Tests\Feature\AuthenticatedTestCase;

class UpdateTeamNameTest extends AuthenticatedTestCase
{
    public function test_team_names_can_be_updated()
    {
        $this->put('/teams/' . $this->user->currentTeam->id, [
            'name' => 'Test Team',
        ]);

        $this->assertCount(1, $this->user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $this->user->currentTeam->fresh()->name);
    }
}
