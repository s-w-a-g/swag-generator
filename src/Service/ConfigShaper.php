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
    /**
     * build app config
     *
     * @param  string $root the directory holding the generator and user sources
     *
     * @return array
     */
    public static function shapeConfig($root)
    {
        $config = Yaml::parse(file_get_contents($root.'/generator/config.yml'));

        $config['srcRoot']  = new \SplFileInfo($root.'/'.$config['srcRoot']);
        $config['dataRoot'] = new \SplFileInfo($root.'/'.$config['dataRoot']);
        $config['siteRoot'] = new \SplFileInfo($root.'/'.$config['siteRoot']);

        return $config;
    }
}
