<?php

namespace Tests\Feature\ApiTokens;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    public function test_api_token_permissions_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['server:create', 'site:create'],
        ]);

        $this->put('/user/api-tokens/'.$token->id, [
            'name' => $token->name,
            'permissions' => [
                'server:delete',
                'site:delete',
            ],
        ]);

        $this->assertTrue($user->fresh()->tokens->first()->can('server:delete'));
        $this->assertFalse($user->fresh()->tokens->first()->can('server:create'));
        $this->assertFalse($user->fresh()->tokens->first()->can('site:create'));
    }
}
