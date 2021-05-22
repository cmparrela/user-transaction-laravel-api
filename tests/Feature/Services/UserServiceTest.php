<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    /** @var UserService */
    private $userService;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $app = $this->createApplication();

        $this->userService = $app->make(UserService::class);
        $this->user = User::factory()->create()->fresh();
    }

    public function testGetAll()
    {
        $users = $this->userService->getAll();
        $this->assertEquals([$this->user->toArray()], $users->toArray());
    }

    public function testCreateSuccess()
    {
        $data = [
            'name' => 'people',
            'cpf_cnpj' => '95611442390',
            'email' => 'people@teste.com',
            'password' => 'password',
        ];
        $user = $this->userService->createNew($data);

        $this->assertNotNull($user->id);
    }

    public function testFindById()
    {
        $user = $this->userService->findById($this->user->id);

        $this->assertEquals($this->user->toArray(), $user->toArray());
    }

    public function testUpdateById()
    {
        $this->user->name = 'people edited';
        $this->user->cpf_cnpj = '75217861304';
        $this->user->email = 'people_edited@teste.com';

        $editedUser = $this->userService->updateById($this->user->id, $this->user->toArray());

        $this->assertEquals($this->user->toArray(), $editedUser->toArray());
    }

    public function testDeleteById()
    {
        $result = $this->userService->deleteById($this->user->id);
        $this->assertTrue($result);
    }

}
