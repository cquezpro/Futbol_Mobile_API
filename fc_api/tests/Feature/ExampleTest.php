<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    function get_list_of_all_user()
    {
        $user = factory(User::class)->create();

        $this->be($user);
        $response = $this->actingAs($user, 'api')
            ->get('/v1/users', [
            'Accept'         => 'application/json',
            'X-localization' => 'es',
            'X-Api-Key'      => 'fvGtSPhjm7UsBp6UWkJPtwMgoBPFdgHYQugXGTT8PrACXg9dBV0bbk668GWgVkcVKORjeqZzCltKOAJmkXNh92Oeb7BMFDY3JuD57coAcQOy4MUrPu4X1J8jT0NDdYuVMHmFhwZhIipivyTeYvsZKwEQ87JcMculOjUweCFFD80i0M4mP7x1Klh7bck2GTFz6tLL2sPBt12D2uF4W2akn8l0JxcsinHQl8cnsbeZzfWHERvEBV1IcA',
            'X-Client-Id'    => '856963',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Users list',
                'data' => [
                    'users' => []
                ]
            ]);
    }
}
