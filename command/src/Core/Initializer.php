<?php

namespace Core;


class Initializer
{
    use MessageTrait;

    function __construct()
    {
        $this->setMessenger();
    }

    /**
     * проверка метода по имени как указывается в консоли
     * @param $str
     * @return bool
     */
    static function checkMethod($str): bool
    {
        return file_exists(__DIR__ . '/Commands/' . ucfirst($str) . 'Command.php');
    }

    /**
     * выполнение вызванного метода
     * @param ConsoleRead $data
     * @return void
     */
    function exec(ConsoleRead $data): void
    {
        if (!$this->checkMethod($data->method))
            $this->sendMessage('Ошибка вызова метода');
        $className = 'Core\Commands\\' . ucfirst($data->method . 'Command');
        $executor = new $className();

        $executor->execute($data->arguments, $data->params);
    }
}