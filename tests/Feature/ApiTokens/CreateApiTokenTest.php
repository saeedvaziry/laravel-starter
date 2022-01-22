<?php

namespace Tests\Feature\ApiTokens;

use App\Models\User;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    public function test_api_tokens_can_be_created()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $this->post('/user/api-tokens', [
            'name' => 'Test Token',
            'permissions' => [
                'server:create',
                'server:delete',
            ],
        ]);

        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertEquals('Test Token', $user->fresh()->tokens->first()->name);
        $this->assertTrue($user->fresh()->tokens->first()->can('server:create'));
        $this->assertFalse($user->fresh()->tokens->first()->can('site:create'));
    }
}
