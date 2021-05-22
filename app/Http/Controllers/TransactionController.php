<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalance;
use App\Exceptions\NotificationFailException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use App\Services\UserService;
use Exception;
use Illuminate\Routing\Controller;

class TransactionController extends Controller
{

    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(TransactionRequest $request, UserService $userService)
    {
        try {
            $data = [
                'value' => $request->input('value'),
                'payer_id' => $request->input('payer'),
                'payee_id' => $request->input('payee'),
            ];
            $transaction = $this->transactionService->createNewTransaction($userService, $data);
            return response()->json($transaction, 201);

        } catch (InsufficientBalance | UnauthorizedException | NotificationFailException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
