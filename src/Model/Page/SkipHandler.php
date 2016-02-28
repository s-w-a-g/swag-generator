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
class SkipHandler implements PageHandlerInterface
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
        $relativePath = $this->mirror->getSrcFileRelativePath($file);
        echo "\nSkipping  ".$relativePath;
    }
}
