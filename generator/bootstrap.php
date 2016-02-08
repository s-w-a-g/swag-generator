<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

require_once __DIR__.'/autoload.php';

use Swag\Service\AssetCopier;
use Swag\Service\ConfigShaper;
use Swag\Service\PageRenderer;
use Swag\Service\SourceTreeMimicker;

$config = ConfigShaper::shapeConfig(realpath(__DIR__.'/..'), '/config.yml');

$mirror = new SourceTreeMimicker($config['srcRoot'], $config['siteRoot']);
$copier = new AssetCopier($mirror);
$renderer = new PageRenderer($mirror);

$srcTree = new \RecursiveIteratorIterator(
    new \RecursiveDirectoryIterator($config['srcRoot'], \FilesystemIterator::SKIP_DOTS)
);
