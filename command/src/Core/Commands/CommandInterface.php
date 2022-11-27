<?php

namespace Core\Commands;

interface CommandInterface
{

    /**
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
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments = array(), array $options = array()): void;

}
