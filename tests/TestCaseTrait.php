<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Test;

use Swag\Model\Data\DataBuilder;
use Swag\Model\Data\Handler\DirectoryHandler;
use Swag\Model\Data\Handler\MarkdownHandler;
use Swag\Model\Data\Handler\YamlHandler;
use Swag\Service\Notifier;
use Symfony\Component\Console\Output\ConsoleOutput;

trait TestCaseTrait
{
    public function getFixturesDir()
    {
        return __DIR__.'/fixtures/';
    }

    /**
     * Get a fake DataBuilder
     *
     * @param string $dataDirectory
     *
     * @return \Swag\Model\Data\DataBuilder
     */
    public function getDataBuilder($dataDirectory = null)
    {
        $dir = new \SplFileInfo($dataDirectory ? : $this->getFixturesDir().'data');
        $output   = new ConsoleOutput();
        $notifier = new Notifier($output);

        $dataBuilder = new DataBuilder($dir, $notifier);

        $dataBuilder->addDataHandler(new DirectoryHandler());
        $dataBuilder->addDataHandler(new YamlHandler());
        $dataBuilder->addDataHandler(new MarkdownHandler());

        return $dataBuilder;
    }
}
