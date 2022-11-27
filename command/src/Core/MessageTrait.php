<?php

namespace Core;

trait MessageTrait
{
    protected object $messenger;


    /**
     * задание класса для отправки сообщений
     * класс сообщений
     * @param $obj
     * @return void
     */
    public function setMessenger($obj = null)
    {
        if ($obj)
            $this->messenger = $obj;
        else
            $this->messenger = Message::createInstance();
    }

    /**
     * отправка сообщений пользователю
     * @param $str
     * @return void
     */
    protected function sendMessage($str): void
    {
        $this->messenger->sendMessage($str);
    }
}