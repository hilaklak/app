<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Letter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LetterTest extends TestCase
{
    use RefreshDatabase;


    public function test_a_user_can_create_a_letter()
    {

        $this->withoutExceptionHandling();

        $letter = Letter::factory()->make()->toArray();

        $this->post("/letters", $letter)
            ->assertRedirect('/')
            ->assertStatus(302);

        $this->assertDatabaseHas('letters', $letter);
    }

    public function test_a_user_can_get_letters()
    {

        $letter = Letter::factory()->create();

        $response = $this->get('/letters');

        $response->assertSee($letter->title);
    }

    public function test_a_user_can_read_a_letter()
    {

        $letter = Letter::factory()->create();

        $response = $this->get("/letters/$letter->id");

        $response->assertSee($letter->title)
            ->assertSee($letter->description);
    }

    public function test_authenticated_user_can_create_a_letter()
    {

        //Given that we have an authenticated user
        $this->actingAs(User::factory()->create());
        //and a letter object
        $letter = Letter::factory()->make()->toArray();
        //when a post request is sent to the endpoint
        $response = $this->post("/letters/create", $letter);
        //it gets saved into the database
        $this->assertEquals(1, Letter::all()->count());
        //redirect to show the letter
        $response->assertRedirect();
        // and a success response is returned
        $response->assertStatus(302);
    }

    public function test_unauthorized_user_cannot_create_letter()
    {

        //Given a task object
        $letter = Letter::factory()->make()->toArray();
        //When an unauthorized user send post request to the endpoint
        $response = $this->post("/letters/create", $letter);
        //It should redirect back to /login
        $response->assertRedirect("/login")
            ->assertStatus(302);
    }

    public function test_a_letter_requires_a_title()
    {

        $this->actingAs(User::factory()->create());

        $letter = Letter::factory()->make([
            'title' => null
        ])->toArray();

        $this->post('/letters/create', $letter)->assertSessionHasErrors('title');
    }

    public function testProductNameIsRequired()
    {
        $letter = Letter::factory()->make([
            'title' => null
        ])->toArray();

        $response = $this->post('/api/products', $letter);
        $response->assertStatus(422);
    }


    public function test_a_letter_requires_a_description()
    {

        $this->actingAs(User::factory()->create());

        $letter = Letter::factory()->make([
            'description' => null
        ])->toArray();

        $this->post('/letters/create', $letter)->assertSessionHasErrors('description');
    }

    public function test_authorized_user_can_update_a_letter()
    {
        $this->actingAs(User::factory()->create());

        $letter = Letter::factory()->create(['user_id' => Auth::id()]);

        $letter->title = "Updated Letter Title";

        $this->put("/letters/$letter->id", $letter->toArray());

        $this->assertDatabaseHas('letters', ['id' => $letter->id, 'title' => 'Updated Letter Title']);
    }

    public function test_unauthorized_user_can_not_update_a_letter()
    {

        $this->actingAs(User::factory()->create());

        $letter = Letter::factory()->create();

        $letter->title = "Unauthroized Action";

        $this->put("/letters/$letter->id", $letter->toArray())
            ->assertStatus(403);
    }

    public function test_authorized_user_can_delete_letter()
    {

        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $letter = Letter::factory()->create(['user_id' => Auth()->id()]);

        $response = $this->delete("/letters/$letter->id");

        $this->assertDatabaseMissing("letters", ['id' => $letter->id]);

        $response->assertStatus(302)
            ->assertRedirect("/letters");
    }

    public function test_unauthorized_user_cannot_delete_letter()
    {

        $this->actingAs(User::factory()->create());

        $letter = Letter::factory()->create();

        $this->delete("/letters/$letter->id")
            ->assertStatus(403);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
