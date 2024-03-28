<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * make admin user type loged In
     */
    private function makeAdminlogin()
    {
        $user = User::factory()
            ->admin()
            ->create();
        $this->actingAs($user);
    }

    /**
     * make normall user type loged In
     */
    private function makeUserLogin()
    {
        $user = User::factory()
            ->user()
            ->create();
        $this->actingAs($user);
    }
}
