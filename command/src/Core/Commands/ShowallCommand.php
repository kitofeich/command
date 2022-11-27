<?php

namespace Core\Commands;


class ShowallCommand extends Command
{
    protected string $sort = '';
    protected int $limit = 0;
    protected array $trueSort = array('ask', 'desc', 'rand');

    /**
     * @return string
     */
    public function getDefaultDescription(): string
    {
        $str = 'Данная команда выводит все переданные параметры и атрибуты
            Дополнительные параметры:
                -sort принимает варинты значений ask, desc, rand
                -limit ограничивает вывод числа элементов из набора
            Дополнительные атрибуты:
                - arguments выведет переданное в аргумента
                - options  выведет переданное в опциях
        ';
        return $str;
    }

    /**
     * @param array $arguments
     * @param array $options
     * @return void
     */
    protected function checkParams(array $arguments = array(), array $options = array()): void
    {

        if (isset($options['sort'])) {
            if (in_array($options['sort'], $this->trueSort))
                $this->sort = $options['sort'];
            else
                $this->sendMessage('Ошибка в переданном параметре для сортировки');
        }

        if (isset($options['limit'])) {
            $this->limit = number_format($options['limit'], 0);
        }
    }

    /**
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments = array(), array $options = array()): void
    {
        parent::execute($arguments, $options);
        $ar = array();
        $ar[] = 'Введенная команда Showall' . PHP_EOL;

        if (isset($arguments['arguments']) or !isset($arguments['options']))
            $ar = array_merge($ar, $this->getAttrAr($arguments));

        if (!isset($arguments['arguments']) or isset($arguments['options']))
            $ar = array_merge($ar, $this->getAttrOpt($options));

        $this->sendMessage($ar);
    }

    /**
     * ф-ця сортировки
     * @param $ar
     * @return array
     */
    protected function sortArray($ar): array
    {
        switch ($this->sort) {
            case 'ask':
                asort($ar);
                break;
            case 'desc':
                arsort($ar);
                break;
            case 'rand':
                shuffle($ar);
                break;
        }
        return $ar;
    }

    /**
     * преобразование аргументов в удобоваримый вид
     * @param $arguments
     * @return array
     */
    protected function getAttrAr($arguments): array
    {
        $ar[] = 'Введенные аргументы:';
        $arguments = $this->sortArray($arguments);
        $i = 0;

        foreach ($arguments as $str) {
            $i++;
            $ar[] = ' - ' . $str;
            if ($this->limit === $i)
                break;
        }

        return $ar;
    }

    /**
     * преобразование параметров в удобоваримый вид
     * @param $options
     * @return array
     */
    protected function getAttrOpt($options): array
    {
        $ar = $this->getRecArrOpt($options);
        array_unshift($ar, 'Введенные параменты:');
        return $ar;
    }

    /**
     * циклическая сборка вложенного массива
     * @param array $options
     * @param bool $needKey
     * @return array
     */
    protected function getRecArrOpt(array $options, bool $needKey = true): array
    {
        $ar = array();
        $options = $this->sortArray($options);

        $i = 0;
        foreach ($options as $key => $str) {
            $i++;
            if ($needKey)
                $ar[] = ' - ' . $key;

            if (is_array($str))
                $ar[] = $this->getRecArrOpt($str, false);
            else
                $ar[] = '    - ' . $str;

            if ($this->limit === $i)
                break;
        }

        return $ar;
    }
}