<?php

namespace AfzalH\UserApi\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class BaseTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->defaultHeaders['X-Requested-With'] = 'XMLHttpRequest';
        $this->defaultHeaders['accept'] = 'application/json, text/plain, */*';
        $this->defaultHeaders['content-type'] = 'application/json;charset=UTF-8';
    }
}
