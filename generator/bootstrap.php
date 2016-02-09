<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

require_once __DIR__.'/autoload.php';

use Swag\Generator;
use Swag\Service\AssetCopier;
use Swag\Service\ConfigShaper;
use Swag\Service\PageRenderer;
use Swag\Service\SourceTreeMimicker;

$config = ConfigShaper::shapeConfig(realpath(__DIR__.'/..'), '/config.yml');

$mirror = new SourceTreeMimicker($config['srcRoot'], $config['siteRoot']);
$copier = new AssetCopier($mirror);

$loader = new \Twig_Loader_Filesystem($config['srcRoot']);
$twig = new \Twig_Environment($loader, [
    'cache' => false,
]);

$renderer = new PageRenderer($twig, $mirror);

$app = new Generator($config, $copier, $renderer);
