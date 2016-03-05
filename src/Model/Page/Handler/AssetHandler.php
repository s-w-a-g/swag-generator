<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page\Handler;

use Swag\Model\Page\Handler\PageHandlerInterface;
use Swag\Model\FileSystem\FileSystem;

/**
 * Renders twig templates to pages
 */
class AssetHandler implements PageHandlerInterface
{
    /**
     * Service handling consistency between source and destination directories
     *
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * __construct
     *
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(\SplFileInfo $file)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function processFile(\SplFileInfo $file, $data = null)
    {
        $relativePath = $this->fileSystem->getSrcFileRelativePath($file);
        $destination  = $this->fileSystem->generateDestinationPathName($relativePath);

        $this->fileSystem->ensureDestinationDirectoryIsWritable($relativePath);

        echo "\nCopying   ".$relativePath;
        copy($file, $destination);
    }
}
