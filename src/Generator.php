<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag;

use Swag\Exception\InitException;
use Swag\Exception\SwagException;
use Swag\Model\Data\DataFactory;
use Swag\Model\Page\Engine;
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

            $container->setParameter('console_output', $output);

            $resources = $container
                ->get('swag.resources_conformer')
                ->ensureResourcesAreWorkable($userDirectory, $destination);

            $container->setParameter('data_directory', $resources['data']);
            $container->setParameter('pages_directory', $resources['pages']);
            $container->setParameter('destination_directory', $resources['destination']);

            $pageEngine  = $container->get('swag.page_engine');
            $dataBuilder = $container->get('swag.data_builder');
        } catch (InitException $e) {
            $output->writeln('<error>'.$e->getMessage().'</>');
            die(1);
        }


        try {
            $data = $dataBuilder->processData();
            $pageEngine->setData($data)->processPages();
        } catch (SwagException $e) {
            $output->writeln('<error> '.$e->getMessage().' </>');
            die(2);
        }
    }
}
