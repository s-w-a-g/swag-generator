<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Service;

use Swag\Exception\InitException;
use Symfony\Component\Yaml\Yaml;

/**
 * Handle config depending on
 * - user information filled in config.yml
 * - folder location on system
 */
class ResourcesConformer
{
    /**
     * build app config
     *
     * @param  string $resourcesLocation the directory holding the generator and user sources
     * @param  string $config            Yml config file for the app
     * @throws InitException
     *
     * @return array
     */
    public static function init($resourcesLocation, $config)
    {
        $resourcesLocation = new \SplFileInfo($resourcesLocation);

        if (!$resourcesLocation->isDir()) {
            throw new InitException(sprintf(
                '--source option is not a valid directory: %s',
                $resourcesLocation
            ));
        }

        $config = Yaml::parse(file_get_contents($config));

        $readableDirectories = [
            'data'  => $config['user']['data'],
            'pages' => $config['user']['pages'],
        ];
        foreach ($readableDirectories as $key => $subDir) {
            $dir = new \SplFileInfo($resourcesLocation.'/'.$subDir);
            self::ensureDirIsReadable($dir);
            $resources[$key] = $dir;
        }

        $destination = new \SplFileInfo($resourcesLocation.'/'.$config['user']['destination']);
        self::ensureDirIsWritable($destination);
        $resources['destination'] = $destination;

        return $resources;
    }

    /**
     * Check for existence of a required directory
     *
     * @param  \SplFileInfo $dir
     *
     * @throws InitException
     */
    private function ensureDirIsReadable(\SplFileInfo $dir)
    {
        // check if dir is an actual directory
        if (!$dir->isDir()) {
            throw new InitException(sprintf(
                "'%s' is not a directory in source directory",
                $dir
            ));
        }

        // check if dir is readable
        if (!$dir->isReadable()) {
            throw new InitException(sprintf(
                "Cannot read '%s' in source directory. Check for access rights.",
                $dir
            ));
        }
    }

    /**
     * Check for a directory existence otherwise try to create it
     *
     * @param  \SplFileInfo $dir
     * @throws InitException
     */
    private function ensureDirIsWritable(\SplFileInfo $dir)
    {
        // check if dir is an actual directory
        if (!$dir->isDir()) {
            // Let's create it
            try {
                self::makeDir($dir, 0700, true);
            } catch (\ErrorException $e) {
                throw new InitException(sprintf(
                    "Cannot create '%s' in source directory. Check for access rights.",
                    $dir
                ));
            }
        }
    }

    /**
     * Create a Dir with Warning handling
     *
     * @param  \SplFileInfo $dir
     * @throws \ErrorException
     */
    private function makeDir(\SplFileInfo $dir)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline, array $errcontext) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        mkdir($dir, 0700, true);

        restore_error_handler();
    }
}
