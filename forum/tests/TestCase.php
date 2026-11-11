<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        AssertableInertia::macro('hasResource', function (
            string $key,
            JsonResource $resource
        ) {
            $expected = $resource->response()->getData(true);

            $this->has($key);

            Assert::assertEquals(
                $expected,
                $this->toArray()['props'][$key]
            );

            return $this;
        });

        AssertableInertia::macro('hasPaginatedResource', function (
            string $key,
            JsonResource $resource
        ) {
            $expected = $resource->response()->getData(true);

            $this->has($key);

            Assert::assertEquals(
                $expected,
                $this->toArray()['props'][$key]
            );

            return $this;
        });
    }
}
