<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */
namespace Swag\Test\Model\Data;

use Swag\Model\Data\DataBuilder;
use Swag\Service\Notifier;
use Swag\Test\TestCaseTrait;
use Symfony\Component\Console\Output\ConsoleOutput;

class DataBuilderTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    /**
     * @expectedException Swag\Model\Data\Exception\InvalidDataFileException
     */
    public function testGetHandlerForFile()
    {
        $dir         = new \SplFileInfo($this->getFixturesDir().'data');
        $output      = new ConsoleOutput();
        $notifier    = new Notifier($output);
        $dataBuilder = new DataBuilder($dir, $notifier);

        $dataBuilder->getHandlerForFile($dir);
    }

    public function testProcessData()
    {
        $dir = $this->getFixturesDir().'data/process';
        $dataBuilder = $this->getDataBuilder($dir);

        $expected = [
            'swag' => [
                'test' => 'php unit',
                'tree' => [
                    'entry1' => 'one',
                    'entry2' => 'two',
                ]
            ]
        ];
        $data = $dataBuilder->processData();

        $this->assertEquals($expected, $data);
    }
}
