<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class PayerIsShop implements Rule
{

    public function passes($attribute, $value)
    {
        $user = User::find($value);
        return ($user->type === User::TYPE_NORMAL);
    }

    public function message()
    {
        return 'The payer can\'t be a shop.';
    }
}
