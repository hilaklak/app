<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_application_returns_a_notfound_response(): void
    {
        $response = $this->get('/this-route-not-exists');

        $response->assertStatus(404);
    }
}
