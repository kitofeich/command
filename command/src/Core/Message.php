<?php

namespace Core;


class Message
{
    private static ?object $instance = null;

    /**
     * создание объекта сообщений
     * @return object
     */
    static public function createInstance(): object
    {
        if (self::$instance === null) {
            self::$instance = new Message();
        }

        return self::$instance;
    }


    /**
     * вывод строки на экран
     * @param string $str
     * @param $is_test
     * @return void
     */
    protected function writeMessage(string $str, $is_test = 0): void
    {
        echo $str . PHP_EOL;
        if (!$is_test) {
            $this->endExecute();
        }

    }


    /**
     * отправить сообщение не важно строку или массив
     * @param array|string $val
     * @param $is_test
     * @return void
     */
    public function sendMessage(array|string $val, int $is_test = 0): void
    {
        $mess = self::createInstance();
        if (is_array($val))
            $val = $mess->arrayToString($val);
        $mess->writeMessage($val, $is_test);

    }

    /**
     * рекурсивный трансформатор массива в строку
     * @param array $ar
     * @return string
     */
    protected function arrayToString(array $ar): string
    {
        $message = '';
        foreach ($ar as $str) {
            if (is_array($str))
                $message .= $this->arrayToString($str);
            else
                $message .= $str . PHP_EOL;
        }
        return $message;
    }

    //так как не предполагается интерактивное взаимодействие, после вывода выход
    protected function endExecute(): void
    {
        exit();
    }
}