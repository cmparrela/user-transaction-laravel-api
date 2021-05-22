<?php
namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\Validator;

class AccountService
{
    protected $model;

    public function __construct(Account $account)
    {
        $this->model = $account;
    }

    public function createNew(array $attributes)
    {
        Validator::make($attributes, [
            'balance' => 'numeric',
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
        ])->validate();

        return $this->model->create($attributes)->fresh();
    }

    public function deposit(Account $account, $amount)
    {
        $account->balance = $account->balance + $amount;
        $account->save();
    }

    public function withdrawal(Account $account, $amount)
    {
        $account->balance = $account->balance - $amount;
        $account->save();
    }
}
