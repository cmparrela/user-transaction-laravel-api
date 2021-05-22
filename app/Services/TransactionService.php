<?php

namespace App\Services;

use App\Exceptions\InsufficientBalance;
use App\Exceptions\UnauthorizedException;
use App\Jobs\NotificationJob;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AccountService;
use Illuminate\Support\Facades\DB;

class TransactionService
{

    private $transaction;
    private $userService;
    private $authorizationService;
    private $accountService;

    public function __construct(
        Transaction $transaction,
        UserService $userService,
        AuthorizationService $authorizationService,
        AccountService $accountService
    ) {
        $this->transaction = $transaction;
        $this->userService = $userService;
        $this->authorizationService = $authorizationService;
        $this->accountService = $accountService;
    }

    private function payerHasMoney($amount, User $payer)
    {
        return $payer->account->balance > $amount;
    }

    public function createNewTransaction(UserService $userService, $data)
    {
        $payer = $this->userService->findById($data['payer_id']);
        $payee = $this->userService->findById($data['payee_id']);

        if (!$this->payerHasMoney($data['value'], $payer)) {
            throw new InsufficientBalance();
        }

        $dataTransaction = [
            'payee_account_id' => $payee->account->id,
            'payer_account_id' => $payer->account->id,
            'value' => $data['value'],
        ];

        $transaction = DB::transaction(function () use ($dataTransaction) {
            $transaction = $this->transaction->create($dataTransaction);

            if (!$this->authorizationService->isApproved($transaction->id)) {
                throw new UnauthorizedException();
            }

            $this->accountService->withdrawal($transaction->payerAccount, $dataTransaction['value']);
            $this->accountService->deposit($transaction->payeeAccount, $dataTransaction['value']);

            return $transaction;
        });

        NotificationJob::dispatch($payer->email, 'Your payment was successfully sent');
        NotificationJob::dispatch($payee->email, 'You received a payment');

        return $transaction;
    }
}
