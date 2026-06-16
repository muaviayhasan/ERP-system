<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The root path redirects guests toward the dashboard (which in turn
     * requires authentication), and the login screen is reachable.
     */
    public function test_the_application_redirects_and_login_is_reachable(): void
    {
        $this->get('/')->assertRedirect(route('dashboard'));
        $this->get('/login')->assertOk();
    }
}
