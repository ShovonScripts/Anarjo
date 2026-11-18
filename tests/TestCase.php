<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->cleanupOutputBuffers();

        parent::tearDown();
    }

    protected function cleanupOutputBuffers(): void
    {
        do {
            $level = ob_get_level();
            if ($level > 1) {
                ob_end_clean();
            }
        } while (ob_get_level() > 1);
    }
}
