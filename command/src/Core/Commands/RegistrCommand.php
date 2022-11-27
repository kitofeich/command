<?php

namespace Core\Commands;

use Exception;

class RegistrCommand extends Command
{

    protected string $path = __DIR__ . '/';
    protected string $newClassName;
    protected string $newFileName;

    /**
     * проверяем наличие пути и существования файлы
     *
     * @param array $arguments
     * @param array $options
     * @return void
     */
    protected function checkParams(array $arguments = array(), array $options = array()): void
    {

        if (empty($options['path']))
            $this->sendMessage('Не указан путь до файла');

        if (!is_file($options['path']))
            $this->sendMessage('Проверьте путь, файл не найден');

        $ar = explode('/', $options['path']);
        $this->newFileName = array_pop($ar);
        $this->newClassName = 'Core\Commands\\' . str_replace('.php', '', $this->newFileName);

        if (file_exists($this->path . $this->newFileName))
            $this->sendMessage('Такая команда уже существует');

    }

    /**
     * сама регистрация, основная проверка класса , создание описания
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments = array(), array $options = array()): void
    {
        parent::execute($arguments, $options);

        $this->checkNewClass($options['path']);

        $cmd = new $this->newClassName();
        $cmd->setDescription($cmd->getDefaultDescription());

        if (isset($arguments['todel']))
            $this->delFile($options['path']);

        $this->sendMessage('Выполнено');
    }

    /**
     * пытаемся добавить класс и проверить работоспособность
     * //todo тут бы еще эксепшены обработать
     * @param string $path
     * @return void
     */
    protected function checkNewClass(string $path): void
    {
        try {
            require_once($path);
            copy($path, $this->path . $this->newFileName);

            if (get_parent_class($this->newClassName) !== 'Core\Commands\Command')
                throw new Exception('новая команда должна наследоваться от класса Command' . $this->path . $this->newFileName . '   ' . $this->newClassName . '    ' . is_subclass_of($this->newClassName, Core\Commands\Command::class));
            $cmd = new $this->newClassName();
            $cmd->getDefaultDescription();
        } catch (Exception $e) {
            unlink($this->path . $this->newFileName);
            $this->sendMessage('При попытке выполнения произошла следеющая ошибка: ' . $e->getMessage());
        }
    }


    /**
     * удаление перенесенного файла
     * @param $path
     * @return void
     */
    protected function delFile($path)
    {
        try {
            unlink($path);
        } catch (Exception $e) {
            $this->sendMessage('Не хватило прав на удаление');
        }
    }
}