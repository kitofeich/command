<?php

namespace Core\Commands;

interface CommandInterface
{

    /**
     * тут храниться базовое описание команды, вызывается при добавлении
     * @return string
     */
    public function getDefaultDescription(): string;

    /**
     * @param string $str
     * @return void
     */
    public function setDescription(string $str): void;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * основная ф-ция команды
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments = array(), array $options = array()): void;

}
