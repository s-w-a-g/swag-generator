<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Service;

use Symfony\Component\Yaml\Yaml;

/**
 * Handle config depending on
 * - user information filled in config.yml
 * - folder location on system
 */
class ConfigShaper
{
    static public function shapeConfig($root)
    {
        $config = Yaml::parse(file_get_contents($root.'/generator/config.yml'));

        $config['srcRoot'] = $root.'/'.$config['srcRoot'];
        $config['siteRoot'] = $root.'/'.$config['siteRoot'];

        return $config;
    }

}
