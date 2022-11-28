<?php

namespace Core\Commands;
require_once __DIR__ . '/../src/autoload.php';

use Core\Message;
use PHPUnit\Framework\TestCase;

class ListCommandTest extends TestCase
{

    public function testGetDescription()
    {
        $mockMessage = $this->createMock(Message::class)
            ->method('sendMessage')->will($this->returnArgument(0));

        $lc = new ListCommand();
        $lc->setMessenger($mockMessage);

        $this->assertMatchesRegularExpression("@список всех доступных@", $lc->getDescription());
    }


    public function testSetDescription()
    {
        $mockMessage = $this->createMock(Message::class)
            ->method('sendMessage')->will($this->returnArgument(0));

        $lc = new ListCommand();
        $lc->setMessenger($mockMessage);
        $lc->setDescription('новое описание');

        //$this->assertMatchesRegularExpression("@ыполнен@", $lc->setDescription('новое описание'));
        $str = file_get_contents(__DIR__ . '/../src/Core/Commands/Description/ListCommandDescription.txt');
        $this->assertEquals('новое описание',$str);
        $lc->setDescription($lc->getDefaultDescription());

    }


    public function testExecute()
    {

        $mockMessage = $this->getMockBuilder(Message::class)
                        ->onlyMethods(['createInstance', 'sendMessage'])->getMock();
        $mockMessage->method('sendMessage')->willReturnArgument(0);
        $mockMessage->method('createInstance')->willReturnSelf();
        $ar = array('Доступные для выполнения команды:', ' - ListCommand', ' - RegistrCommand', ' - ShowallCommand');
        $mockMessage->expects($this->once())->method('sendMessage')->with($ar);

        $lc = new ListCommand();
        $lc->setMessenger($mockMessage);
        $lc->execute();

    }

}
