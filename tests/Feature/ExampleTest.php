<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_method_not_allowed_response()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
