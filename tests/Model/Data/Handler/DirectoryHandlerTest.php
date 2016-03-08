<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */
namespace Swag\Test\Model\Data\Handler;

use Swag\Model\Data\DataBuilder;
use Swag\Model\Data\Handler\DirectoryHandler;
use Swag\Model\Data\Handler\YamlHandler;
use Swag\Test\TestCaseTrait;

class DirectoryHandlerTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testApply()
    {
        $fixturesDir = new \SplFileInfo($this->getFixturesDir().'data/test-dir');
        $handler = new DirectoryHandler();

        $this->assertTrue($handler->apply($fixturesDir));

        $file = new \SplFileInfo('test.yml');
        $this->assertFalse($handler->apply($file));
    }

    public function testGetValue()
    {
        $dir = new \SplFileInfo($this->getFixturesDir().'data/test-dir');

        $dirHandler = new DirectoryHandler();

        $ymlHandler = new YamlHandler();

        $dataBuilder = new DataBuilder(new \SplFileInfo($this->getFixturesDir().'data'));
        $dataBuilder->addDataHandler($dirHandler);
        $dataBuilder->addDataHandler($ymlHandler);

        $data = $dirHandler->getValue($dir);

        $expectedValue = [
            'swag' => [
                'foo' => 'bar',
                'bar' => 'foo',
            ]
        ];

        $this->assertEquals($expectedValue, $data);
    }
}
