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
class SkipHandler implements PageHandlerInterface
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
        // discard hidden files
        if (strpos($file->getBasename(), '.') === 0) {
            return true;
        }

        // Discard Twig files
        if ($file->getExtension() === 'twig') {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function processFile(\SplFileInfo $file, $data = null)
    {
        $relativePath = $this->fileSystem->getSrcFileRelativePath($file);
        echo "\nSkipping  ".$relativePath;
    }
}
