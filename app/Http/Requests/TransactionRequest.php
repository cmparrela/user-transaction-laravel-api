<?php

namespace App\Http\Requests;

use App\Rules\PayerIsShop;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $ruleExistOnDatable = 'exists:users,id,deleted_at,NULL';
        return [
            'value' => ['required', 'numeric'],
            'payer' => ['bail', 'required', $ruleExistOnDatable, new PayerIsShop()],
            'payee' => ['required', $ruleExistOnDatable, 'different:payer_id'],
        ];
    }
}
