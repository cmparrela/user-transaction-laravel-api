<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $userOne;
    private $userTwo;
    private $userShop;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeUsers();
    }

    protected function makeUsers()
    {
        $account = Account::factory()->count(1);
        User::factory()->count(15)->has($account)->create();

        $users = User::getByType(User::TYPE_NORMAL);
        $this->userOne = $users[1];
        $this->userTwo = $users[2];

        $user = User::findByType(User::TYPE_SHOP);
        $this->userShop = $user;
    }

    public function testTransactionSuccess()
    {
        $data = [
            'value' => 10,
            'payer' => $this->userOne->id,
            'payee' => $this->userTwo->id,
        ];

        $payerBalance = $this->userOne->account->balance;
        $payeeBalance = $this->userTwo->account->balance;

        $response = $this->json('POST', '/transactions', $data);

        $newValueOfPayer = (float) number_format($payerBalance - $data['value'], 2, '.', '');
        $newValueOfPayee = (float) number_format($payeeBalance + $data['value'], 2, '.', '');

        $response
            ->assertStatus(201)
            ->assertJsonFragment(['balance' => $newValueOfPayer])
            ->assertJsonFragment(['balance' => $newValueOfPayee]);
    }

    public function testValidationIfPayerIsShop()
    {
        $data = [
            'value' => 10,
            'payer' => $this->userShop->id,
            'payee' => $this->userTwo->id,
        ];
        $response = $this->json('POST', '/transactions', $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('payer')
            ->assertJsonFragment(["The payer can't be a shop."]);
    }

    public function testValidationIfPayerHasNoMoney()
    {
        $this->userOne->account->balance = 0;
        $this->userOne->account->save();

        $data = [
            'value' => 50,
            'payer' => $this->userOne->id,
            'payee' => $this->userTwo->id,
        ];
        $response = $this->json('POST', '/transactions', $data);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(["Insufficient balance to perform the transaction"]);
    }
}
