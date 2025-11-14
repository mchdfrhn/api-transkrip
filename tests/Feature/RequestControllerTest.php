<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Request as RequestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;
    protected $otherUser;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->otherUser = User::factory()->create(['role' => 'user']);
    }

    /**
     * Test user can view their own requests
     */
    public function test_user_can_view_their_own_requests()
    {
        // Create requests for the user
        $requests = RequestModel::factory()->count(3)->create(['user_id' => $this->user->id]);

        // User views their own requests
        $response = $this->actingAs($this->user)
            ->getJson("/api/requests/user/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'type',
                        'queue',
                        'request',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test user cannot view other user's requests
     */
    public function test_user_cannot_view_other_user_requests()
    {
        // Create requests for another user
        RequestModel::factory()->count(2)->create(['user_id' => $this->otherUser->id]);

        // User tries to view other user's requests
        $response = $this->actingAs($this->user)
            ->getJson("/api/requests/user/{$this->otherUser->id}");

        $response->assertStatus(403)
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    /**
     * Test admin can view any user's requests
     */
    public function test_admin_can_view_any_user_requests()
    {
        // Create requests for a regular user
        RequestModel::factory()->count(4)->create(['user_id' => $this->user->id]);

        // Admin views the user's requests
        $response = $this->actingAs($this->admin)
            ->getJson("/api/requests/user/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);
    }

    /**
     * Test unauthenticated user cannot view requests
     */
    public function test_unauthenticated_user_cannot_view_requests()
    {
        $response = $this->getJson("/api/requests/user/{$this->user->id}");

        $response->assertStatus(401);
    }

    /**
     * Test endpoint returns empty array when user has no requests
     */
    public function test_endpoint_returns_empty_array_when_user_has_no_requests()
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/requests/user/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    /**
     * Test requests are loaded with all necessary relations
     */
    public function test_requests_are_loaded_with_relations()
    {
        $request = RequestModel::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/requests/user/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'role'
                        ],
                        'response'
                    ]
                ]
            ]);
    }
}
