<?php

namespace Tests\Feature\Team;

use App\Mail\TeamInvitation;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\AuthenticatedTestCase;

class InviteTeamMemberTest extends AuthenticatedTestCase
{
    public function test_team_members_can_be_invited_to_team()
    {
        Mail::fake();

        $this->post('/teams/' . $this->user->currentTeam->id . '/members', [
            'email' => 'test@example.com',
            'permissions' => ['resource:create'],
        ]);

        Mail::assertSent(TeamInvitation::class);

        $this->assertCount(1, $this->user->currentTeam->fresh()->teamInvitations);
    }

    public function test_team_member_invitations_can_be_cancelled()
    {
        $invitation = $this->user->currentTeam->teamInvitations()->create([
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $this->delete('/team-invitations/' . $invitation->id);

        $this->assertCount(0, $this->user->currentTeam->fresh()->teamInvitations);
    }
}
