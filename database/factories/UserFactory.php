<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $cpfCnpj = [$this->faker->cpf(false), $this->faker->cnpj(false)];
        $types = [User::TYPE_NORMAL, User::TYPE_SHOP];

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(),
            'cpf_cnpj' => $cpfCnpj[array_rand($cpfCnpj)],
            'type' => $types[array_rand($types)]
        ];
    }
}
