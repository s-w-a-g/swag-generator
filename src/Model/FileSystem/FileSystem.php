<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\FileSystem;

use Swag\Model\FileSystem\Exception\MakeDirException;

/**
 * Handle file paths and directory consistency
 * between the source tree and the static website folder
 */
class FileSystem
{
    /**
     * The assets root directory
     *
     * @var \SplFileInfo
     */
    private $srcRoot;

    /**
     * The website root folder
     *
     * @var \SplFileInfo
     */
    private $siteRoot;

    /**
     * __construct
     *
     * @param \SplFileInfo $srcRoot  The assets root directory
     * @param \SplFileInfo $siteRoot The static website root directory
     */
    public function __construct(\SplFileInfo $srcRoot, \SplFileInfo $siteRoot)
    {
        $this->srcRoot  = $srcRoot;
        $this->siteRoot = $siteRoot;
    }

    /**
     * Return relative path of a source file relative to the source root dir
     *
     * @param  \SplFileInfo $file source file
     *
     * @return string the relative path to the source root dir
     */
    public function getSrcFileRelativePath(\SplFileInfo $file)
    {
        return substr($file->getPathName(), strlen($this->srcRoot) + 1);
    }

    /**
     * Generate destination pathname based on website root directory
     *
     * @param  string $relativePath of source file
     *
     * @return string the absolute full path in website root directory for the file
     */
    public function generateDestinationPathName($relativePath)
    {
        return $this->siteRoot.'/'.$relativePath;
    }

    /**
     * Ensures destination directory for asset exists. Creates it otherwise.
     *
     * @param string $relativePath full path of a file
     */
    public function ensureDestinationDirectoryIsWritable($relativePath)
    {
        $subDirs = explode(DIRECTORY_SEPARATOR, dirname($relativePath));
        $path    = $this->siteRoot;

        foreach ($subDirs as $subDir) {
            $path .= DIRECTORY_SEPARATOR.$subDir;
            if (!is_dir($path)) {
                self::makeDir($path);
            }
        }
    }

    /**
     * Create a directory with Warning handling
     *
     * @param  \SplFileInfo|string $dir
     *
     * @throws MakeDirException
     */
    public static function makeDir($dir)
    {
        set_error_handler(function () use ($dir) {
            throw new MakeDirException($dir);
        });

        mkdir($dir, 0700, true);

        restore_error_handler();
    }
}
