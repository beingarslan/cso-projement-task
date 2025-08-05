<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_page_loads_successfully()
    {
        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_can_create_user_via_ajax()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/users', $userData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User created successfully!',
                 ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_can_update_user_via_ajax()
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'user_id' => $user->id,
        ];

        $response = $this->putJson("/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User updated successfully!',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_can_delete_user_via_ajax()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User deleted successfully!',
                 ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_validation_fails_for_invalid_data()
    {
        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123', // Less than 6 characters
        ];

        $response = $this->postJson('/users', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_email_must_be_unique_on_create()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/users', $userData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_can_show_user_data()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $user->id,
                         'name' => $user->name,
                         'email' => $user->email,
                     ],
                 ]);
    }
}
