<?php

namespace Core;

use PHPUnit\Framework\TestCase;

class Initializer
{
    use MessageTrait;

    function __construct()
    {
        $this->setMessenger();
    }

    static function checkMethod($str) : bool
    {
        return file_exists(__DIR__ . '/Commands/' . ucfirst($str) . 'Command.php' );
    }

    function exec(ConsoleRead $data) : void
    {
        if(!$this->checkMethod($data->method))
            $this->sendMessage('Ошибка вызова метода');
        $className  = 'Core\Commands\\'.ucfirst($data->method . 'Command');
        $executor = new $className();

        $executor->execute($data->arguments, $data->params);
    }
}