<?php

namespace Core\Commands;

use Core\MessageTrait;

abstract class Command implements CommandInterface
{
    use MessageTrait;

    protected string $path = __DIR__ . '/';
    private string $pathDesc = __DIR__ . '/Description/';
    private string $tail = 'Description.txt';
    private string $message = '';
    private bool $needExecute = true;

    function __construct()
    {
        $this->setMessenger();
    }

    /**
     * проверки необходимого минимума параматеров
     *
     * @param array $arguments
     * @param array $options
     * @return void
     */
    protected function checkParams(array $arguments = array(), array $options = array()): void
    {

    }

    /**
     * @return string
     */
    public function getDefaultDescription(): string
    {
        return 'sample description';
    }

    /**
     * @param string $str
     * @return void
     */
    public function setDescription(string $str): void
    {
        file_put_contents($this->getPathDesc(), $str);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $description = '';

        $path = $this->getPathDesc();

        if (file_exists($path))
            $description = file_get_contents($path);
        return $description;
    }

    /**
     * первичное выполнение в родителе, хелп, установка описания,
     * вызов проверки присланных атрибутов на минимально необходимое команде
     *
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments = array(), array $options = array()): void
    {

        if (isset($arguments['help']))
            $this->sendMessage($this->getDescription());

        if (isset($arguments['setDescription'])) {
            if (!$arguments['setDescription'])
                $this->sendMessage('для смены описания его надо поместить в агрумент description, например "[description=\'Новое описание команды\']"');
            $this->setDescription($options['description']);
            $this->sendMessage('Выполнено');
        }

        $this->checkParams($arguments, $options);
    }

    /**
     * вывод сообщения пользователю
     * @param $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->message .= $message;
        $this->needExecute = false;
    }


    /**
     * определение пути до класса потомка для работы ф-ций описания
     * @return string
     */
    protected function getPathDesc(): string
    {
        $ar = explode('\\', get_class($this));
        $path = $this->pathDesc . array_pop($ar) . $this->tail;

        return $path;
    }
}