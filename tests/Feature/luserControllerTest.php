<?php

namespace Tests\Feature\Http\Controller;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class luserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $createData = [
            'name' => 'testuser',
            'age' => '22',
            'address' => '5463 test address'
        ];
        $response = $this->post('api/users', $createData);
        $response->assertStatus(201);
    }
}
