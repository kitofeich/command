<?php

namespace Core\Commands;


class ListCommand extends Command
{

    /**
     * @return string
     */
    public function getDefaultDescription(): string
    {
        return 'Данная команда выводит список всех доступных команд';
    }

    /**
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments = array(), array $options = array()): void
    {
        parent::execute($arguments, $options);

        $arrayMethods = array('Доступные для выполнения команды:');
        $ar = scandir($this->path);

        unset($ar[0], $ar[1]);

        foreach ($ar as $method) {
            switch ($method) {
                case 'Command.php':
                case 'CommandInterface.php':
                case 'Description':
                    break;

                default:
                    $arrayMethods[] = ' - ' . str_replace('.php', '', $method);
            }
        }

        $this->sendMessage($arrayMethods);
    }
}