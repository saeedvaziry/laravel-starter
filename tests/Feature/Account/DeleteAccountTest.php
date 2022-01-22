<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    public function test_user_accounts_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->create());

        $this->delete('/user', [
            'password' => 'password',
        ]);

        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->create());

        $this->delete('/user', [
            'password' => 'wrong-password',
        ]);

        $this->assertNotNull($user->fresh());
    }
}
