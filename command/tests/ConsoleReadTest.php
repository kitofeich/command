<?php

namespace Core;
require_once __DIR__ . '/../src/autoload.php';

use PHPUnit\Framework\TestCase;

class ConsoleReadTest extends TestCase
{

    /**
     * @dataProvider arDataProvider
     */
    public function testRead($ar)
    {

        $cr = new ConsoleRead($ar);
        $this->assertEquals('list', $cr->method);

    }

    /**
     * @dataProvider arDataProviderMore
     */
    public function testMainMethodsRead($ar)
    {

        $cr = new ConsoleRead($ar);
        $this->assertEquals($ar[1], $cr->method);

    }

    /**
     * @dataProvider arDataProviderMore
     */
    public function testAttrRead($ar)
    {

        $cr = new ConsoleRead($ar);
        $this->assertContains('dog', $cr->arguments);
        $this->assertArrayHasKey('pig', $cr->params);

    }

    public function arDataProvider()
    {
        return array(
            array(
                array('', 'list', '{dog}'),
                array(''),
                array('', 'list', 'dog', '{cat}', '[pig=1]', '[pig={one,two,three}}]'),
            )
        );
    }

    public function arDataProviderMore()
    {
        return array(
            array(
                array('', 'list', '[pig=1]', '{dog}'),
                array('', 'showall', 'dog', '{cat}', '[pig=1]', '[wing={one,two,three}}]'),
                array('', 'register', 'dog', 'cat', '[pig=1]', '[wing={one,two,three}}]'),
            )
        );
    }
}
