<?php

namespace Core;
require_once __DIR__ . '/../src/autoload.php';

use PHPUnit\Framework\TestCase;

class InitializerTest extends TestCase
{

    public function testOkCheckMethod()
    {
        $str = 'list';
        $cr = new Initializer();
        $this->assertEquals('list', $cr->checkMethod($str));
    }

    public function testFailCheckMethod()
    {
        $str = 'list';
        $cr = new Initializer();
        $this->assertEquals('list2', $cr->checkMethod($str));
    }
}
