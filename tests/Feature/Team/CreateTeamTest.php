<?php

namespace Tests\Feature\Team;

use Tests\Feature\AuthenticatedTestCase;

class CreateTeamTest extends AuthenticatedTestCase
{
    public function test_teams_can_be_created()
    {
        $this->post('/teams', [
            'name' => 'Test Team',
        ]);

        $this->assertCount(2, $this->user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $this->user->fresh()->ownedTeams()->latest('id')->first()->name);
    }
}
