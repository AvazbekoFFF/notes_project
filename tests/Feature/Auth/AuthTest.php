<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @group Auth
     */
    public function test_register()
    {
        $response = $this->post('api/register', [
            'email' => 'admin@admin.bro.com',
            'password' => 'password',
            'name' => 'admin'
        ]);
        $response->assertStatus(200);
    }

    /**
     * @group Auth
     */
    public function test_login(){
        $response = $this->post('api/register', [
            'email' => 'admin@admin.bro.com',
            'password' => 'password',
            'name' => 'admin'
        ]);
        $response = $this->post('api/login', [
            'email' => 'admin@admin.bro.com',
            'password' => 'password'
        ]);
        $response->assertStatus(200);
    }

    /**
     * @group Auth
     */
    public function test_logout()
    {
        $responseRegister = $this->post('api/register', [
            'email' => 'admin@admin.bro.com',
            'password' => 'password',
            'name' => 'admin'
        ]);
        $token = $responseRegister['access_token'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get(route('notes.index'));
        $response->assertStatus(200);
    }

}
