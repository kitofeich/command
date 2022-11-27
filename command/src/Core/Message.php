<?php

namespace Core;


class Message
{
    private static ?object $instance = null;

    /**
     * @return object
     */
    static public function createInstance(): object
    {
        if(self::$instance === null){
            self::$instance = new Message();
        }

        return self::$instance;
    }

    /**
     * @param string $str
     * @return void
     */
    protected function writeMessage(string $str, $is_test = 0): void
    {
        echo $str . PHP_EOL;
        if(!$is_test){
            $this->endExecute();
        }

    }


    /**
     * @param array|string $val
     * @return void
     */
    public function sendMessage(array|string $val, $is_test = 0): void
    {
        $mess = self::createInstance();
        if(is_array($val))
            $val = $mess->arrayToString($val);
        $mess->writeMessage($val, $is_test);

    }

    /**
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

    protected function endExecute(): void
    {
        exit();
    }
}