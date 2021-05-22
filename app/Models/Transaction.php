<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['payee_account_id', 'payer_account_id', 'value', 'updated_at', 'created_at'];

    public function payerAccount()
    {
        return $this->belongsTo(Account::class, 'payer_account_id');
    }

    public function payeeAccount()
    {
        return $this->belongsTo(Account::class, 'payee_account_id');
    }
}
