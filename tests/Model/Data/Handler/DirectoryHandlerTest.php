<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */
namespace Swag\Test\Model\Data\Handler;

use Swag\Model\Data\DataBuilder;
use Swag\Model\Data\Handler\DirectoryHandler;
use Swag\Model\Data\Handler\YamlHandler;
use Swag\Service\Notifier;
use Swag\Test\TestCaseTrait;
use Symfony\Component\Console\Output\StreamOutput;

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
    public function testDuplicateKeys()
    {
        $dir = $this->getFixturesDir().'data';
        $dataBuilder = $this->getDataBuilder();

        $dirHandler = new DirectoryHandler();
        $dataBuilder->addDataHandler($dirHandler);

        $data = $dirHandler->getValue(new \SplFileInfo($dir));
    }

    public function testSkipInvalidFiles()
    {
        $dir         = new \SplFileInfo($this->getFixturesDir().'data/test-dir');
        $stream      = fopen('php://memory', 'a', false);
        $output      = new StreamOutput($stream);
        $notifier    = new Notifier($output);
        $dataBuilder = new DataBuilder($dir, $notifier);
        $dirHandler  = new DirectoryHandler();
        $dataBuilder->addDataHandler($dirHandler);

        $dirHandler->getValue(new \SplFileInfo($dir));

        rewind($output->getStream());
        $expected = 'Skipping invalid file: '.$dir.'/swag.yml'.PHP_EOL.PHP_EOL;
        $this->assertEquals($expected, stream_get_contents($output->getStream()));
    }
}
