<?php

namespace Tests\Feature\Team;

use App\Models\User;
use Tests\Feature\AuthenticatedTestCase;

class UpdateTeamMemberRoleTest extends AuthenticatedTestCase
{
    public function test_team_member_roles_can_be_updated()
    {
        $this->user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['permissions' => ['server:create']]
        );

        $this->put('/teams/' . $this->user->currentTeam->id . '/members/' . $otherUser->id, [
            'permissions' => ['server:create'],
        ]);

        $this->assertTrue($otherUser->fresh()->hasTeamPermission(
            $this->user->currentTeam->fresh(), 'server:create'
        ));
    }

    public function test_only_team_owner_can_update_team_member_roles()
    {
        $this->user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['permissions' => ['server:create']]
        );

        $this->actingAs($otherUser);

        $this->put('/teams/' . $this->user->currentTeam->id . '/members/' . $otherUser->id, [
            'permissions' => ['server:create'],
        ]);

        $this->assertTrue($otherUser->fresh()->hasTeamPermission(
            $this->user->currentTeam->fresh(), 'server:create'
        ));
    }
}
