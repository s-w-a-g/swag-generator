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
    public function getDataBuilder($dataDirectory)
    {
        $dir = new \SplFileInfo($dataDirectory);

        $dirHandler = new DirectoryHandler();
        $ymlHandler = new YamlHandler();
        $mdHandler  = new MarkdownHandler();

        $dataBuilder = new DataBuilder(new \SplFileInfo($this->getFixturesDir().'data'));
        $dataBuilder->addDataHandler($dirHandler);
        $dataBuilder->addDataHandler($ymlHandler);
        $dataBuilder->addDataHandler($mdHandler);

        return $dataBuilder;
    }
}
