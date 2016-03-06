<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

use Swag\Model\Data\Handler\YamlHandler;

class YamlHandlerTest extends PHPUnit_Framework_TestCase
{
    public function testSetContent()
    {
        $yml = __DIR__.'/../../../fixtures/Data/swag.yml';
        $file = new \SplFileInfo($yml);

        $handler = new YamlHandler($file);

        $this->assertTrue($handler->isValid());
        $this->assertEquals('swag', $handler->getKey());

        $result = [
            'test' => 'php unit',
            'tree' => ['entry1' => 'one', 'entry2' => 'two'],
        ];

        $this->assertEquals($result, $handler->getValue());
    }
}
