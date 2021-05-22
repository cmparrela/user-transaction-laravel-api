<?php

namespace Tests\Feature\Models;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use DatabaseMigrations;

    public function testForeignKey()
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create()->fresh();

        $this->assertEquals($user->name, $account->user->name);
    }
}
