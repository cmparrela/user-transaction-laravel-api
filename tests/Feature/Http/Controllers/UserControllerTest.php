<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;
    use TestValidations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $app = $this->createApplication();

        $this->user = User::factory()->create()->fresh();
    }

    public function testIndex()
    {
        $response = $this->get('/users');
        $response
            ->assertStatus(200)
            ->assertJson([$this->user->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get("/users/{$this->user->id}");
        $response
            ->assertStatus(200)
            ->assertJson($this->user->toArray());
    }

    public function testCreateNormalType()
    {
        $data = [
            'name' => 'normal',
            'cpf_cnpj' => '95611442390',
            'email' => 'shop@teste.com',
        ];
        $response = $this->json('POST', '/users', $data + ['password' => 'password']);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data + ['type' => 'normal']);
    }

    public function testCreateShopType()
    {
        $data = [
            'name' => 'shop',
            'cpf_cnpj' => '55546767000102',
            'email' => 'shop@teste.com',
        ];
        $response = $this->json('POST', '/users', $data + ['password' => 'password']);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data + ['type' => 'shop']);
    }

    public function testCreateValidationRequired()
    {
        $response = $this->json('POST', '/users', []);
        $this->assertInvalidationFields($response, ['name', 'cpf_cnpj', 'email', 'password'], 'required');
    }

    public function testCreateValidationMax()
    {
        $data = [
            'name' => str_repeat('a', 101),
            'cpf_cnpj' => str_repeat('a', 15),
            'email' => str_repeat('a', 51),
            'password' => str_repeat('a', 66),
        ];
        $response = $this->json('POST', '/users', $data);
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 100]);
        $this->assertInvalidationFields($response, ['cpf_cnpj'], 'max.string', ['max' => 14]);
        $this->assertInvalidationFields($response, ['email'], 'max.string', ['max' => 50]);
        $this->assertInvalidationFields($response, ['password'], 'max.string', ['max' => 65]);
    }

    public function testCreateValidationEmail()
    {
        $data = [
            'email' => 'email_invalido',
        ];
        $response = $this->json('POST', '/users', $data);
        $this->assertInvalidationFields($response, ['email'], 'email');
    }

    public function testCreateValidationUnique()
    {
        $data = [
            'cpf_cnpj' => $this->user->cpf_cnpj,
            'email' => $this->user->email,
        ];
        $response = $this->json('POST', '/users', $data);
        $this->assertInvalidationFields($response, ['cpf_cnpj', 'email'], 'unique');
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'fulano',
            'cpf_cnpj' => '25827485446',
        ];
        $response = $this->json('PUT', "/users/{$this->user->id}", $data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', "/users/{$this->user->id}");
        $response->assertStatus(204);

        $user = User::find($this->user->id);
        $this->assertNull($user);

        $userDeleted = User::findTrashedById($this->user->id);
        $this->assertNotNull($userDeleted);
    }

}
