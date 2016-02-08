<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Service;

/**
 * Handle file paths and directory consistency
 * between the source tree and the static website folder
 */
class SourceTreeMimicker
{
    /**
     * The assets root directory
     *
     * @var string
     */
    private $srcRoot;

    /**
     * The website root folder
     *
     * @var string
     */
    private $siteRoot;

    /**
     * __construct
     *
     * @param $srcRoot The assets root directory
     */
    public function __construct($srcRoot, $siteRoot)
    {
        $this->srcRoot = $srcRoot;
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
        echo $this->srcRoot." ÷ ".$file."\n";
        return substr($file->getPathName(), strlen($this->srcRoot) + 1);
    }

    /**
     * Generate destination pathname based on website root directory
     *
     * @param  string $basename of source file
     *
     * @return string the absolute full path in website root directory for the file
     */
    public function generateDestinationPathName($basename)
    {
        return $this->siteRoot.'/'.$basename;
    }

    /**
     * Insures destination directory for asset exists. Creates it otherwise.
     *
     * @param  \SplFileInfo $file
     */
    public function ensureDestinationDirectoryIsWritable($absolutePath)
    {
        $destDir = dirname($absolutePath);
        echo ">>>".$absolutePath.">>>".$destDir."\n";

        if (!is_dir($destDir) && !mkdir($destDir, 0700, true)) {
            throw new \Exception(sprintf(
                "Unable to create the directory: %s",
                $destDir
            ), 1);
        }
    }

}
