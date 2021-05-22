<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'accounts';
    protected $fillable = ['balance', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
