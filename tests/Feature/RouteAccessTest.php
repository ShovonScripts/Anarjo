<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_without_acting_as(): void
    {
        $this->assertEquals(200, $this->get('/')->status());
        $this->assertEquals(200, $this->get('/books')->status());
    }

    public function test_with_acting_as(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(200, $this->get('/')->status());
        $this->assertEquals(200, $this->get('/books')->status());
    }
}
