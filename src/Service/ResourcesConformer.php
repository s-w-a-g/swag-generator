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
     * app config (located at the app root)
     * @var array
     */
    private $config;

    /**
     * Construct
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * build app config
     *
     * @param  string $userDirectory The user directory holding source and processed pages
     * @param  string $destination   The destination directory for processed pages
     * @throws InitException
     *
     * @return array
     */
    public function ensureResourcesAreWorkable($userDirectory, $destination)
    {
        // Check for user directory
        $userDirectory = new \SplFileInfo($userDirectory);

        if (!$userDirectory->isDir()) {
            throw new InitException(sprintf(
                'source argument is not a valid directory: %s',
                $userDirectory
            ));
        }

        // Check assets directory is readable
        if (empty($this->config['assets'])) {
            $assets = $userDirectory;
        } else {
            $assets = new \SplFileInfo($userDirectory.'/'.$this->config['assets']);
            self::ensureDirIsReadable($assets);
        }

        // Check assets' subdirectories are readable
        $readableDirectories = [
            'data'  => $assets.DIRECTORY_SEPARATOR.$this->config['data'],
            'pages' => $assets.DIRECTORY_SEPARATOR.$this->config['pages'],
        ];

        foreach ($readableDirectories as $key => $subDir) {
            $dir = new \SplFileInfo($subDir);
            self::ensureDirIsReadable($dir);
            $resources[$key] = $dir;
        }

        // Check for destination directory
        $destination = $destination ? : $this->config['destination'];
        $destination = new \SplFileInfo($userDirectory.DIRECTORY_SEPARATOR.$destination);
        self::ensureDirIsWritable($destination);

        // Check for potential collision in directory structure
        if ($destination->getRealPath() === $userDirectory->getRealPath()) {
            $collision = $resources['pages']->getRealPath().DIRECTORY_SEPARATOR.$assets->getBasename();
            $collision = new \SplFileInfo($collision);

            if ($collision->isDir()) {
                throw new InitException(sprintf(
                    "[%s] directory would overwrite the assets directory needed by the Swag Generator.",
                    $collision->getRealPath()
                ));
            }
        }

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
                "[%s] does not exist or is not a directory.",
                $dir
            ));
        }

        // check if dir is readable
        if (!$dir->isReadable()) {
            throw new InitException(sprintf(
                "Cannot read [%s] in source directory. Check for access rights.",
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
