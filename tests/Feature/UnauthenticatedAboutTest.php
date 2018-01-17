<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnauthenticatedAboutTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    
    public function testAbout()
    {
        // test attempt to see about page without authentication
        $route = route('about');
        $response = $this->get($route);
        $response->assertStatus(302);
        
    }
}