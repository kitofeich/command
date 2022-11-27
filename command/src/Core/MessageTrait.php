<?php

namespace Core;

trait MessageTrait {
    protected object $messenger;

    public function setMessenger($obj = null){
        if($obj)
            $this->messenger = $obj;
        else
            $this->messenger = Message::createInstance();
    }

    protected function sendMessage($str):void
    {
        $this->messenger->sendMessage($str);
    }
}