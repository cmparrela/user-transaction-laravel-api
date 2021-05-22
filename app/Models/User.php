<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    use HasFactory;

    const TYPE_NORMAL = 'normal';
    const TYPE_SHOP = 'shop';

    protected $table = 'users';
    protected $fillable = ['name', 'cpf_cnpj', 'email', 'password', 'type', 'updated_at', 'created_at', 'deleted_at'];
    protected $hidden = ['password'];

    public function account()
    {
        return $this->HasOne(Account::class);
    }

    public static function findTrashedById(int $id)
    {
        return self::onlyTrashed()->find($id);
    }

    public static function getByType(string $type)
    {
        return self::where('type', '=', $type)->get();
    }

    public static function findByType(string $type)
    {
        return self::where('type', '=', $type)->first();
    }

}
