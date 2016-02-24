<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

use Swag\Model\Page\PageHandlerInterface;
use Swag\Service\SourceTreeMimicker;

/**
 * Renders twig templates to pages
 */
class AssetHandler implements PageHandlerInterface
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
        $relativePath = $this->mirror->getSrcFileRelativePath($file);
        $destination  = $this->mirror->generateDestinationPathName($relativePath);

        $this->mirror->ensureDestinationDirectoryIsWritable($destination);

        echo "\nCopying   ".$relativePath;
        copy($file, $destination);
    }
}
