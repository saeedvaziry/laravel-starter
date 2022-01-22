<?php

namespace Tests\Feature\ApiTokens;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteApiTokenTest extends TestCase
{
    public function test_api_tokens_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['server:create', 'server:delete'],
        ]);

        $this->delete('/user/api-tokens/' . $token->id);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
