<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cuisine\Cuisine;
use App\Models\Restaurant\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CuisineApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_active_cuisines()
    {
        // Create test cuisines
        $activeCuisine = Cuisine::factory()->create(['status' => Cuisine::STATUS_ACTIVE]);
        $inactiveCuisine = Cuisine::factory()->create(['status' => Cuisine::STATUS_INACTIVE]);

        // Test Kiosk platform
        $response = $this->get('/api/kiosk/cuisines');
        $response->assertStatus(200)
                ->assertJsonCount(1, 'data'); // Only active cuisine should be returned

        // Test Android platform  
        $response = $this->get('/api/android/cuisines');
        $response->assertStatus(200);

        // Test iOS platform
        $response = $this->get('/api/ios/cuisines');
        $response->assertStatus(200);

        // Test Website platform
        $response = $this->get('/api/website/cuisines');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_cuisine_by_slug()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'georgian-cuisine',
            'status' => Cuisine::STATUS_ACTIVE
        ]);

        $response = $this->get('/api/website/cuisines/georgian-cuisine');
        
        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $cuisine->id,
                        'slug' => 'georgian-cuisine',
                        'status' => Cuisine::STATUS_ACTIVE
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_cuisine()
    {
        $response = $this->get('/api/website/cuisines/non-existent-cuisine');
        
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_for_inactive_cuisine()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'inactive-cuisine',
            'status' => Cuisine::STATUS_INACTIVE
        ]);

        $response = $this->get('/api/website/cuisines/inactive-cuisine');
        
        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_get_restaurants_by_cuisine()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'italian-cuisine',
            'status' => Cuisine::STATUS_ACTIVE
        ]);

        $activeRestaurant = Restaurant::factory()->create(['status' => 'active']);
        $inactiveRestaurant = Restaurant::factory()->create(['status' => 'inactive']);

        // Attach restaurants to cuisine
        $cuisine->restaurants()->attach($activeRestaurant->id);
        $cuisine->restaurants()->attach($inactiveRestaurant->id);

        $response = $this->get('/api/website/cuisines/italian-cuisine/restaurants');
        
        $response->assertStatus(200)
                ->assertJsonCount(1, 'data'); // Only active restaurant should be returned
    }

    /** @test */
    public function it_can_get_top_10_restaurants_by_cuisine()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'asian-cuisine',
            'status' => Cuisine::STATUS_ACTIVE
        ]);

        // Create 15 active restaurants with different ratings
        $restaurants = Restaurant::factory()->count(15)->create(['status' => 'active']);
        
        // Attach all restaurants to cuisine
        foreach ($restaurants as $restaurant) {
            $cuisine->restaurants()->attach($restaurant->id);
        }

        $response = $this->get('/api/website/cuisines/asian-cuisine/restaurants/top10');
        
        $response->assertStatus(200)
                ->assertJsonCount(10, 'data'); // Should return exactly 10 restaurants
    }

    /** @test */
    public function it_handles_cuisine_with_no_restaurants()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'empty-cuisine',
            'status' => Cuisine::STATUS_ACTIVE
        ]);

        $response = $this->get('/api/website/cuisines/empty-cuisine/restaurants');
        
        $response->assertStatus(200)
                ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function test_endpoints_work_for_all_platforms()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'test-cuisine',
            'status' => Cuisine::STATUS_ACTIVE
        ]);

        $platforms = ['kiosk', 'android', 'ios', 'website'];
        
        foreach ($platforms as $platform) {
            // Test list endpoint
            $response = $this->get("/api/{$platform}/cuisines");
            $response->assertStatus(200);

            // Test show endpoint
            $response = $this->get("/api/{$platform}/cuisines/test-cuisine");
            $response->assertStatus(200);

            // Test restaurants endpoint
            $response = $this->get("/api/{$platform}/cuisines/test-cuisine/restaurants");
            $response->assertStatus(200);

            // Test top10 restaurants endpoint
            $response = $this->get("/api/{$platform}/cuisines/test-cuisine/restaurants/top10");
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function test_endpoints_return_proper_json_structure()
    {
        $cuisine = Cuisine::factory()->create([
            'slug' => 'json-test-cuisine',
            'status' => Cuisine::STATUS_ACTIVE
        ]);

        // Test list endpoint structure
        $response = $this->get('/api/website/cuisines');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'status'
                        ]
                    ]
                ]);

        // Test single cuisine structure
        $response = $this->get('/api/website/cuisines/json-test-cuisine');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'slug',
                        'status'
                    ]
                ]);
    }
}