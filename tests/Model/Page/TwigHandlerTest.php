<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */
namespace Swag\Test\Model\Page\Handler;

use Swag\Model\Page\Handler\TwigHandler;
use Swag\Test\TestCaseTrait;

class TwigHandlerTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testApply()
    {
        $pagesDir = $this->getFixturesDir().'pages';
        $destDir = $this->getFixturesDir().'destination';

        $twig = $this->getTwigEnvironment($pagesDir);
        $fileSystem = $this->getFilesystem($pagesDir, $destDir);
        $handler = new TwigHandler($twig, $fileSystem);

        $file = new \SplFileInfo('test.md');
        $this->assertFalse($handler->apply($file));
    }
}
