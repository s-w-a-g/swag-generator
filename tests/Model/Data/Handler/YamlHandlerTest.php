<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */
namespace Swag\Test\Model\Data\Handler;

use Swag\Model\Data\Handler\YamlHandler;

class YamlHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $handler = new YamlHandler();

        $fileNames = [
            'test.yml'   => true,
            'test.yaml'  => true,
            'test.other' => false,
        ];

        foreach ($fileNames as $fileName => $bool) {
            $file = new \SplFileInfo($fileName);
            $this->assertEquals($handler->apply($file), $bool);
        }
    }

    public function testSetContent()
    {
        $yml = __DIR__.'/../../../fixtures/Data/swag.yml';
        $file = new \SplFileInfo($yml);

        $handler = new YamlHandler();

        $result = [
            'test' => 'php unit',
            'tree' => ['entry1' => 'one', 'entry2' => 'two'],
        ];

        $this->assertEquals($result, $handler->getValue($file));
    }
}
