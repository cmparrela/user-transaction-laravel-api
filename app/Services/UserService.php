<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService implements CrudServiceInterface
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function createNew($userData)
    {
        Validator::make($userData, [
            'name' => 'required|max:100',
            'cpf_cnpj' => 'required|max:14|unique:users|cpf_cnpj',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|max:65',
        ])->validate();

        $userData['password'] = Hash::make($userData['password']);

        $userData['type'] = 'normal';
        if (strlen(($userData['cpf_cnpj'])) > 11) {
            $userData['type'] = 'shop';
        }

        return $this->model->create($userData)->fresh();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function updateById($id, $data)
    {
        Validator::make($data, [
            'name' => 'max:100',
            'cpf_cnpj' => "max:14|unique:users,cpf_cnpj,$id|cpf_cnpj",
            'email' => "email|max:50|unique:users,email,$id",
            'password' => 'max:15',
            'type' => 'in:normal,shop',
        ])->validate();

        $object = $this->model->find($id);
        $object->fill($data);
        $object->save();
        return $object;
    }

    public function deleteById($id)
    {
        $object = $this->model->find($id);
        if ($object) {
            return $object->delete();
        }
        return false;
    }
}
