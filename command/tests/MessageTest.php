<?php

namespace Core;
require_once __DIR__ . '/../src/autoload.php';

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    public function testSendMessage()
    {

        $ar = array('cat', 'dog');

        $expected = 'cat' . PHP_EOL . 'dog' . PHP_EOL;
        $expected .=  PHP_EOL;

        $this->expectOutputString($expected);

        $ms = new Message();
        $ms->sendMessage($ar, 1);

    }

    public function testStrSendMessage()
    {
        $ar = 'cat';
        $expected = 'cat'. PHP_EOL;

        $this->expectOutputString($expected);

        $ms = new Message();
        $ms->sendMessage($ar, 1);

    }

}
