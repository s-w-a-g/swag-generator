<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Exception\InitException;
use Swag\Exception\SwagException;
use Swag\Model\Data\DataFactory;
use Swag\Model\Page\AssetHandler;
use Swag\Model\Page\Engine;
use Swag\Model\Page\IterativeTwigHandler;
use Swag\Model\Page\SkipHandler;
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
            $resources = ResourcesConformer::init(
                $userDirectory,
                $destination,
                __DIR__.'/../config.yml'
            );

            $mirror = new SourceTreeMimicker($resources['pages'], $resources['destination']);

            $loader = new \Twig_Loader_Filesystem($resources['pages']);
            $twig   = new \Twig_Environment($loader, [
                'cache'      => false,
                'autoescape' => false,
            ]);

            $pageEngine = new Engine();
            $pageEngine->addPageHandler(new IterativeTwigHandler($twig, $mirror));
            $pageEngine->addPageHandler(new TwigHandler($twig, $mirror));
            $pageEngine->addPageHandler(new SkipHandler($mirror));
            $pageEngine->addPageHandler(new AssetHandler($mirror));
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
