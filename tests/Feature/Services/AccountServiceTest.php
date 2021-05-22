<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\AccountService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $accountService;

    protected function setUp(): void
    {
        parent::setUp();
        $app = $this->createApplication();

        $this->accountService = $app->make(AccountService::class);
    }

    public function testCreateSuccess()
    {
        $user = User::factory()->create()->fresh();
        $data = [
            'balance' => 10,
            'user_id' => $user->id,
        ];
        $account = $this->accountService->createNew($data);

        $this->assertNotNull($account->id);
    }

}
