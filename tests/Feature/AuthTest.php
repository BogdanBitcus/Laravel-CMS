<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;



class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bogdan',
            'email' => 'bogdan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'bogdan@example.com',
            'name' => 'Bogdan',
        ]);

        $this->assertDatabaseCount('users', 1);
    }



    public function test_user_can_login(): void {

        $user = User::factory()->create([
            'email' => 'bogdan@example.com',
            'password' => \Hash::make('password123'),
        ]);

        $token = $user->createToken('API')->plainTextToken;

        $response = $this->postJson('/api/login',
            [
                'email' => 'bogdan@example.com',
                'password' => 'password123',
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'API',
        ]);
    }



    public function test_login_rejects_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'bogdan@example.com',
            'password' => \Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'bogdan@example.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }



    public function test_login_rejects_unknown_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'unknown@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }



    public function test_registration_requires_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bogdan',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);

        $this->assertDatabaseCount('users', 0);
    }



    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'bogdan@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Another User',
            'email' => 'bogdan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);

        $this->assertDatabaseCount('users', 1);
    }



    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bogdan',
            'email' => 'bogdan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'password',
            ]);

        $this->assertDatabaseCount('users', 0);
    }




    public function test_password_is_hashed(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Bogdan',
            'email' => 'bogdan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'bogdan@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(
            \Hash::check('password123', $user->password)
        );
    }




    public function test_registration_creates_access_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bogdan',
            'email' => 'bogdan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'API',
        ]);
    }



    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('Test')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/me');

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }


    public function test_guest_cannot_get_profile(): void
    {
        $response = $this->getJson('/api/me');

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
    }




    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('API');

        $plainToken = $token->plainTextToken;
        $tokenId = $token->accessToken->id;

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $tokenId,
            'tokenable_id' => $user->id,
        ]);

        $response = $this->withToken($plainToken)
            ->postJson('/api/logout');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully.',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }



    public function test_logged_out_token_cannot_access_api(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('API')->plainTextToken;

        // Перевіряємо, що токен спочатку працює
        $this->withToken($token)
            ->getJson('/api/me')
            ->assertStatus(200);

        // Logout
        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);

        // Скидаємо cached guards
        Auth::forgetGuards();

        $response = $this->withToken($token)
            ->getJson('/api/me');

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
    }




    public function test_deleted_token_cannot_access_api(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('API')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/me')
            ->assertStatus(200);

        $user->tokens()->delete();

        $this->assertDatabaseCount('personal_access_tokens', 0);

        Auth::forgetGuards();

        $response = $this->withToken($token)
            ->getJson('/api/me');

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
    }



    public function test_deleted_token_is_removed(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('API')->plainTextToken;

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $user->tokens()->delete();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }


    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/
}
