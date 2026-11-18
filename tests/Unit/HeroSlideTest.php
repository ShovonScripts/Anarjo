<?php

namespace Tests\Unit;

use App\Models\HeroSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroSlideTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_slide_can_be_created_without_image(): void
    {
        $slide = HeroSlide::create([
            'link' => '/',
            'sort_order' => 0,
            'status' => true,
            'page' => 'home',
        ]);

        $this->assertDatabaseHas('hero_slides', ['id' => $slide->id]);
        $this->assertNull($slide->fresh()->image);
    }
}
