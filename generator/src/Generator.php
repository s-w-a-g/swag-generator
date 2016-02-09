<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Service\AssetCopier;
use Swag\Service\PageRenderer;

/**
 * App main class
 */
class Generator
{
    /**
     * The app config defining env and other parameters
     * @var array
     */
    private $config;

    /**
     * @var AssetCopier
     */
    private $copier;

    /**
     * @var PageRenderer
     */
    private $renderer;

    /**
     * Construct
     *
     * @param array $config
     * @param AssetCopier $copier
     * @param PageRenderer $renderer
     */
    public function __construct(
        array $config,
        AssetCopier $copier,
        PageRenderer $renderer
    ) {
        $this->config = $config;
        $this->copier = $copier;
        $this->renderer = $renderer;
    }

    /**
     * Main method running the whole generation
     */
    public function generateStaticWebsite()
    {
        # Fetch user data
        $this->getData();

        # Process user layout and assets
        $this->processSourceFiles();
    }

    /**
     * Browse source directory to process user files
     */
    private function processSourceFiles()
    {
        $sourceRoot = $this->config['srcRoot'];
        $ignoredFiles = $this->config['ignored_files'];

        $sourceTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceRoot, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($sourceTree as $file)
        {
            if (!$file->isFile()) {
                continue;
            }

            if (in_array($file->getExtension(), $ignoredFiles)) {
                continue;
            }

            if ($file->getExtension() !== 'twig') {
                $this->copier->copy($file);
                continue;
            }

            $this->renderer->render($file);
        }
    }

    private function getData()
    {
        $dataRoot = $this->config['srcRoot'];
        $ignoredFiles = $this->config['ignored_files'];

        $dataTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dataRoot, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($sourceTree as $file)
        {
            echo $file."\n";
        }
    }
}
