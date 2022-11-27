<?php

namespace Core;

class ConsoleRead
{
    use MessageTrait;

    protected array $userData;
    public string $method = 'list';
    public array $arguments = array();
    public array $params = array();
    private string $errorOptions = 'Ошибка в переданных параметрах ';

    function __construct($argv)
    {
        $this->setMessenger();
        if (count($argv) > 1) {
            $this->userData = $argv;
            $this->read();
        }
    }

    public function read(): void
    {
        $this->setMethod($this->userData[1]);
        unset($this->userData[0], $this->userData[1]);

        if (count($this->userData) > 0) {
            foreach ($this->userData as $str) {
                $this->checkStr($str);
            }
        }
    }

    /**
     * @param string $methodName
     * @return void
     */
    protected function setMethod(string $methodName = ''): void
    {
        if ($this->checkMethod($methodName))
            $this->method = $methodName;
    }

    /**
     * @param string $methodName
     * @return bool
     */
    protected function checkMethod(string $methodName): bool
    {
        $this->checkMethodName($methodName);
        $this->checkMethodExist($methodName);
        return true;
    }

    /**
     * @param string $methodName
     * @return bool
     */
    protected function checkMethodName(string $methodName): bool
    {
        if (!$this->checkName($methodName))
            $this->sendMessage('В имени метода ' . $methodName . ' допущена ошибка');

        return true;
    }

    /**
     * @param string $methodName
     * @return bool
     */
    protected function checkMethodExist(string $methodName): bool
    {
        if (!Initializer::checkMethod($methodName))
            $this->sendMessage('Метод ' . $methodName . ' не существует');

        return true;
    }

    /**
     * @param string $str
     * @return void
     */
    protected function checkStr(string $str): void
    {
        //не может быть меньше
        if (strlen($str) < 3)
            $this->sendMessage($this->errorOptions . $str);

        // пытаемся найти и поставить атрибут
        if ($this->checkArgument($str))
            $this->setArgument($this->parseArgument($str));

        else {
            //не получилось, пытаемся с параметром
            if ($this->checkParam($str))
                $this->setParam($this->parseParam($str));
            else
                $this->sendMessage($this->errorOptions . $str);
        }

    }

    /**
     * @param string $str
     * @return bool
     */
    protected function checkArgument(string $str): bool
    {
        $rz = false;
        // может быть 2 варианта либо окруженная {} либо чистая строка

        //чистая строка
        if ($this->checkClearStr($str))
            return true;
        //окруженная строка
        $checkStart = str_starts_with($str, '{');
        $checkEnd = str_ends_with($str, '}');

        //точно окруженный аргумент
        if ($checkStart and $checkEnd) {
            //после отсечения должна остаться чистая строка
            if (!$this->checkClearStr(substr($str, 1, strlen($str) - 2)))
                $this->sendMessage($this->errorOptions . $str);
            $rz = true;
        }
        return $rz;
    }

    /**
     * @param string $str
     * @return array
     */
    protected function parseArgument(string $str): array
    {
        if (!$this->checkClearStr($str))
            $str = substr($str, 1, strlen($str) - 2);

        $arguments = array();
        //1 буква тоже аргемент
        foreach (explode(',', $str) as $arg) {
            //$arguments[$arg] = $arg;
            $arguments += [ $arg => $arg ];
        }
        return $arguments;
    }

    /**
     * @param array $arguments
     * @return void
     */
    protected function setArgument(array $arguments): void
    {
        $this->arguments += $arguments;
    }

    /**
     * @param string $str
     * @return bool
     */
    protected function checkParam(string $str): bool
    {
        $rz = false;
        $checkStart = str_starts_with($str, '[');
        $checkEnd = str_ends_with($str, ']');

        //точно параметр
        if ($checkStart and $checkEnd) {
            $str = substr($str, 1, strlen($str) - 2);

            $checkOpen = str_contains($str, '[');
            $checkClose = str_contains($str, ']');
            $checkEkv = strpos($str, '=') > 0 ? 1 : 0;

            if ($checkOpen or $checkClose or strlen($str) < 3 or !$checkEkv)
                $this->sendMessage($this->errorOptions . '[' . $str . ']');
            $rz = true;
        }

        return $rz;
    }

    /**
     * @param string $str
     * @return array
     */
    protected function parseParam(string $str): array
    {

        $mainParams = explode('=', substr($str, 1, strlen($str) - 2));
        $this->checkParamName($mainParams[0]);

        return $mainParams;
    }

    /**
     * @param array $parseParam
     * @return void
     */
    protected function setParam(array $parseParam): void
    {
        if (empty($this->params[$parseParam[0]])) {
            $this->params[$parseParam[0]] = $parseParam[1];
        } elseif (is_array($this->params[$parseParam[0]])) {
            $this->params[$parseParam[0]][] = $parseParam[1];
        } else {
            $this->params[$parseParam[0]] = array($this->params[$parseParam[0]], $parseParam[1]);
        }

    }

    /**
     * @param string $str
     * @return bool
     */
    protected function checkParamName(string $str): bool
    {
        if (!$this->checkName($str))
            $this->sendMessage('Ошибка в имени параметра ' . $str);

        return true;
    }

    /**
     * @param string $str
     * @return bool
     */
    protected function checkClearStr(string $str): bool
    {
        //должно совпадать
        return $this->checkName($str);
    }

    /**
     * @param string $str
     * @return bool
     */
    protected function checkName(string $str): bool
    {
        return (bool)preg_match('@^[\w-]+$@', $str);
    }


}