<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

abstract class AuthenticatedTestCase extends TestCase
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($this->user);
    }
}
