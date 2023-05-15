<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanFetchArticles()
    {
        // Create a test user
        $user = User::factory()->create();

        // Generate an API token for the test user
        $token = $user->createToken('testToken')->plainTextToken;

        // Make an API request to fetch articles with the API token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/articles');

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response has the expected structure
        $response->assertJsonStructure([
            '*' => [
                'id',
                'type',
                'sectionId',
                'sectionName',
                'webPublicationDate',
                'webTitle',
                'webUrl',
                'apiUrl',
                'isHosted',
                'pillarId',
                'pillarName',
            ],
        ]);
    }

    public function testUserCannotFetchArticlesWithoutValidToken()
    {
        // Make an API request to fetch articles without providing a valid API token
        $response = $this->getJson('/api/articles');

        // Assert that the response status is 401 (Unauthorized)
        $response->assertStatus(401);
    }
}
