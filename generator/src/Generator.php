<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Service\AssetCopier;
use Swag\Service\PageRenderer;
use Symfony\Component\Yaml\Yaml;

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
     * The user data gathered in an array to be given to the templates
     * @var array
     */
    private $data;

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
     * @param array        $config
     * @param AssetCopier  $copier
     * @param PageRenderer $renderer
     */
    public function __construct(
        array $config,
        AssetCopier $copier,
        PageRenderer $renderer
    ) {
        $this->config   = $config;
        $this->copier   = $copier;
        $this->renderer = $renderer;
    }

    /**
     * Main method running the whole generation
     */
    public function generateStaticWebsite()
    {
        // Fetch user data
        $this->gatherUserData();

        // Process user layout and assets
        $this->processSourceFiles();
    }

    /**
     * Browse source directory to process user files
     */
    private function processSourceFiles()
    {
        $sourceRoot   = $this->config['srcRoot'];
        $ignoredFiles = $this->config['ignored_files'];

        $sourceTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceRoot, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($sourceTree as $file) {
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

            $this->renderer->render($file, $this->data);
        }
    }

    /**
     * Browse data directory to gather data in an array for the template engine
     */
    private function gatherUserData()
    {
        $dataRoot = $this->config['dataRoot'];

        $dataTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dataRoot, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($dataTree as $file) {
            if (!$file->isFile()) {
                continue;
            }

            // handling yml only for now
            if ($file->getExtension() !== 'yml') {
                continue;
            }

            $index = $file->getBasename('.'.$file->getExtension());
            $value = Yaml::parse(file_get_contents($file));

            $this->data[$index] = $value;
        }
    }
}
