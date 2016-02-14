<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Exception\InitException;
use Swag\Model\Data\DataFactory;
use Swag\Model\Page\Engine;
use Swag\Model\Page\AssetHandler;
use Swag\Model\Page\TwigHandler;
use Swag\Service\ResourcesConformer;
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
     * @var PageRenderer
     */
    private $engine;

    /**
     * Construct
     *
     * @param PageRenderer $engine
     */
    public function __construct(
        Engine $engine
    ) {
        $this->engine = $engine;
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

            $loader = new \Twig_Loader_Filesystem($resources['pages']);
            $twig   = new \Twig_Environment($loader, [
                'cache' => false,
            ]);

            $pageEngine = new Engine();
            $pageEngine->addPageHandler(new TwigHandler($twig, $mirror));
            $pageEngine->addPageHandler(new AssetHandler($mirror));
        } catch (InitException $e) {
            $output->writeln('<error>'.$e->getMessage().'</>');
            die;
        }

        $app = new Generator($pageEngine);
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
        $this->engine->processPages($resources['pages'], $data);
    }

    /**
     * Browse data directory to gather data in an array for the template engine
     *
     * @params \SplFileInfo $dataLocation
     *
     * @return array
     */
    private function gatherUserData($dataLocation)
    {
        $data = DataFactory::create($dataLocation);

        return $data->getValue();
    }
}
