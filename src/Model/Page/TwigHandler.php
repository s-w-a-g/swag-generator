<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

use Swag\Exception\InvalidPageException;
use Swag\Model\Page\PageHandlerInterface;
use Swag\Service\SourceTreeMimicker;

/**
 * Renders twig templates to pages
 */
class TwigHandler implements PageHandlerInterface
{
    /**
     * the twig template engine
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Service handling consistency between source and website directories
     *
     * @var SourceTreeMimicker
     */
    private $mirror;

    /**
     * Construct
     *
     * @param \Twig_Environment  $twig
     * @param SourceTreeMimicker $mirror
     */
    public function __construct(\Twig_Environment $twig, SourceTreeMimicker $mirror)
    {
        $this->twig   = $twig;
        $this->mirror = $mirror;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(\SplFileInfo $file)
    {
        return $file->getExtension() === 'twig';
    }

    /**
     * {@inheritdoc}
     */
    public function processFile(\SplFileInfo $file, $data = null)
    {

        $relativePath = $this->mirror->getSrcFileRelativePath($file);
        $destination  = $this->mirror->generateDestinationPathName($this->trimTwigExtension($relativePath));
        $this->mirror->ensureDestinationDirectoryIsWritable($destination);

        echo "\nRendering ".$relativePath;
        $content = $this->twig->render($relativePath, $data);
        file_put_contents($destination, $content);
    }

    /**
     * Remove Twig extension from path
     *
     * @param  string $path
     *
     * @return string
     */
    private function trimTwigExtension($path)
    {
        return substr($path, 0, -5);
    }
}
