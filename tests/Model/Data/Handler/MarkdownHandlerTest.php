<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */
namespace Swag\Test\Model\Data\Handler;

use Swag\Model\Data\Data;
use Swag\Model\Data\Handler\MarkdownHandler;
use Swag\Test\TestCaseTrait;

class MarkdownHandlerTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testApply()
    {
        $handler = new MarkdownHandler();

        $fileNames = [
            'test.md'   => true,
            'test.mdown'  => true,
            'test.other' => false,
        ];

        foreach ($fileNames as $fileName => $bool) {
            $file = new \SplFileInfo($fileName);
            $this->assertEquals($handler->apply($file), $bool);
        }
    }

    public function testGetValue()
    {
        $md = $this->getFixturesDir().'data/markdown.md';
        $file = new \SplFileInfo($md);

        $handler = new MarkdownHandler();
        $data = $handler->getValue($file);

        $this->assertEquals($data->test, 'php unit');
        $this->assertEquals($data->tree, ['entry1' => 'one', 'entry2' => 'two']);

        $this->assertEquals((string) $data, "<h1>Hello</h1>\n\n<p>I am some content</p>\n");
    }
}
