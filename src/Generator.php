<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Exception\InitException;
use Swag\Model\Data\DataFactory;
use Swag\Service\AssetCopier;
use Swag\Service\ResourcesConformer;
use Swag\Service\PageRenderer;
use Swag\Service\SourceTreeMimicker;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param AssetCopier  $copier
     * @param PageRenderer $renderer
     */
    public function __construct(
        AssetCopier $copier,
        PageRenderer $renderer
    ) {
        $this->copier   = $copier;
        $this->renderer = $renderer;
    }

    /**
     * Main app controller
     *
     * @param  string          $source the user resources location
     * @param  OutputInterface $output
     */
    public static function main($source, OutputInterface $output)
    {
        $resourcesLocation = $source;

        try {
            $resources = ResourcesConformer::init($resourcesLocation, __DIR__.'/../config.yml');

            $mirror = new SourceTreeMimicker($resources['pages'], $resources['destination']);

            // Asset Copier
            $copier = new AssetCopier($mirror);

            // Page Renderer
            $loader   = new \Twig_Loader_Filesystem($resources['pages']);
            $twig     = new \Twig_Environment($loader, [
                'cache' => false,
            ]);
            $renderer = new PageRenderer($twig, $mirror);
        } catch (InitException $e) {
            $output->writeln('<error>'.$e->getMessage().'</>');
            die;
        }

        $app = new Generator($copier, $renderer);
        $app->generateStaticWebsite($resources);
    }

    /**
     * Main method running the whole generation
     *
     * @param array $resources The config file defining user resources locations
     */
    public function generateStaticWebsite($resources)
    {
        // Fetch user data
        $data = $this->gatherUserData($resources['data']);

        // Process user layout and assets
        $this->processPages($resources['pages']);
    }

    /**
     * Browse source directory to process user files
     */
    private function processPages($pagesLocation)
    {
        $sourceTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($pagesLocation, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($sourceTree as $file) {
            if (!$file->isFile()) {
                continue;
            }

            // Skip hidden files
            if (strpos($file->getExtension(), '.') === 0) {
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
     *
     * @params \SplFileInfo $dataLocation
     */
    private function gatherUserData($dataLocation)
    {
        $data       = DataFactory::create($dataLocation);
        $this->data = $data->getValue();
    }
}
