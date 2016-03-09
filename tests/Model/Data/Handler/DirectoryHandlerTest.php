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
        $dir = $this->getFixturesDir().'data/test-dir';
        $dataBuilder = $this->getDataBuilder($dir);

        $dirHandler = new DirectoryHandler();
        $dataBuilder->addDataHandler($dirHandler);

        $data = $dirHandler->getValue(new \SplFileInfo($dir));

        $expectedValue = [
            'swag' => [
                'foo' => 'bar',
                'bar' => 'foo',
            ]
        ];

        $this->assertEquals($expectedValue, $data);
    }

    /**
     * @expectedException Swag\Model\Data\Exception\InvalidStructureException
     */
    public function testDuplicateKeys($value='')
    {
        $dir = $this->getFixturesDir().'data';
        $dataBuilder = $this->getDataBuilder($dir);

        $dirHandler = new DirectoryHandler();
        $dataBuilder->addDataHandler($dirHandler);

        $data = $dirHandler->getValue(new \SplFileInfo($dir));
    }
}
