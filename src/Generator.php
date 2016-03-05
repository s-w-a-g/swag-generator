<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Exception\InitException;
use Swag\Exception\SwagException;
use Swag\Model\Data\DataFactory;
use Swag\Model\Page\Handler\AssetHandler;
use Swag\Model\Page\Engine;
use Swag\Model\Page\Handler\IterativeTwigHandler;
use Swag\Model\Page\Handler\SkipHandler;
use Swag\Model\Page\Handler\TwigHandler;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * App main class
 */
class Generator
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * Construct
     *
     * @param Engine $engine
     */
    public function __construct(
        Engine $engine
    ) {
        $this->engine = $engine;
    }

    /**
     * Main app controller
     *
     * @param  string          $source      the user resources location
     * @param  string          $destination Where to place the processed pages
     * @param  OutputInterface $output
     */
    public static function main($source, $destination, OutputInterface $output)
    {
        $userDirectory = $source;

        try {
            $container = new ContainerBuilder();
            $loader    = new YamlFileLoader($container, new FileLocator(__DIR__));
            $loader->load(__DIR__.'/../config.yml');

            $resources = $container
                ->get('swag.resources_conformer')
                ->ensureResourcesAreWorkable($userDirectory, $destination);

            $container->setParameter('data_directory', $resources['data']);
            $container->setParameter('pages_directory', $resources['pages']);
            $container->setParameter('destination_directory', $resources['destination']);

            $fileSystem = $container->get('swag.file_system');
            $twig       = $container->get('swag.template');

            $pageEngine = new Engine();
            $pageEngine->addPageHandler(new IterativeTwigHandler($twig, $fileSystem));
            $pageEngine->addPageHandler(new TwigHandler($twig, $fileSystem));
            $pageEngine->addPageHandler(new SkipHandler($fileSystem));
            $pageEngine->addPageHandler(new AssetHandler($fileSystem));
        } catch (InitException $e) {
            $output->writeln('<error>'.$e->getMessage().'</>');
            die(1);
        }

        $app = new Generator($pageEngine);

        try {
            $app->generateStaticWebsite($resources);
        } catch (SwagException $e) {
            $output->writeln('<error> '.$e->getMessage().' </>');
            die(2);
        }
    }

    /**
     * Main method running the whole generation
     *
     * @param array $resources The config file defining user resources locations
     */
    private function generateStaticWebsite($resources)
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
