<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_profile_photo_url(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password123',
            'profile_photo' => 'profile-photos/avatar.jpg',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()->assertJson([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'profile_photo' => 'profile-photos/avatar.jpg',
                'profile_photo_url' => url('/storage/profile-photos/avatar.jpg'),
            ],
        ]);
    }

    public function test_get_current_user_returns_profile_photo_url(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'profile_photo' => 'profile-photos/avatar.jpg',
        ]);

        UserProfile::factory()->for($user)->create([
            'goal' => 'maintain',
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/user');

        $response->assertOk()->assertJson([
            'id' => $user->id,
            'name' => 'John Doe',
            'profile_photo' => 'profile-photos/avatar.jpg',
            'profile_photo_url' => url('/storage/profile-photos/avatar.jpg'),
            'profile' => [
                'goal' => 'maintain',
            ],
        ]);
    }
}
