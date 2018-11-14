<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function edit_general_information()
    {
        $user = factory(User::class)->create();

        $this->be($user);
        $response = $this->actingAs($user, 'api')
            ->put("v1/users/$user->hash_id/general-information", [
                'gender' => 'male',
                'birthday' => '1985-10-09'
            ], [
                'Accept'         => 'application/json',
                'X-localization' => 'es',
                'X-Api-Key'      => 'fvGtSPhjm7UsBp6UWkJPtwMgoBPFdgHYQugXGTT8PrACXg9dBV0bbk668GWgVkcVKORjeqZzCltKOAJmkXNh92Oeb7BMFDY3JuD57coAcQOy4MUrPu4X1J8jT0NDdYuVMHmFhwZhIipivyTeYvsZKwEQ87JcMculOjUweCFFD80i0M4mP7x1Klh7bck2GTFz6tLL2sPBt12D2uF4W2akn8l0JxcsinHQl8cnsbeZzfWHERvEBV1IcA',
                'X-Client-Id'    => '856963',
            ]);

        $response->assertStatus(200);
    }
}
