<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Service;

/**
 * Copy an asset from src directory to website generated directory
 */
class AssetCopier
{
    /**
     * Service handling consistency between source and destination directories
     *
     * @var SourceTreeMimicker
     */
    private $mirror;

    /**
     * __construct
     *
     * @param SourceTreeMimicker $mirror
     */
    public function __construct(SourceTreeMimicker $mirror)
    {
        $this->mirror = $mirror;
    }

    /**
     * copy an asset from the source folder to its equivalent location in the website file tree
     *
     * @param  \SplFileInfo $file The file to move
     */
    public function copy(\SplFileInfo $file)
    {
        echo 'copying '.$file."\n";
        $relativePath = $this->mirror->getSrcFileRelativePath($file);
        $destination  = $this->mirror->generateDestinationPathName($relativePath);

        $this->mirror->ensureDestinationDirectoryIsWritable($destination);

        copy($file, $destination);
    }
}
