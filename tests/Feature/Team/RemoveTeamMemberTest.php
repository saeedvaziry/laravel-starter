<?php

namespace Tests\Feature\Team;

use App\Models\User;
use Tests\Feature\AuthenticatedTestCase;

class RemoveTeamMemberTest extends AuthenticatedTestCase
{
    public function test_team_members_can_be_removed_from_teams()
    {
        $this->user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['permissions' => ['test']]
        );

        $this->delete('/teams/' . $this->user->currentTeam->id . '/members/' . $otherUser->id);

        $this->assertCount(0, $this->user->currentTeam->fresh()->users);
    }

    public function test_only_team_owner_can_remove_team_members()
    {
        $this->user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['permissions' => ['test']]
        );

        $this->actingAs($otherUser);

        $response = $this->delete('/teams/' . $this->user->currentTeam->id . '/members/' . $this->user->id);

        $response->assertStatus(403);
    }
}
