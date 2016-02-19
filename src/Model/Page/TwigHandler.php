<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

use Swag\Model\Page\PageHandlerInterface;
use Swag\Service\SourceTreeMimicker;
use Symfony\Component\Yaml\Yaml;

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
    protected $twig;

    /**
     * Service handling consistency between source and website directories
     *
     * @var SourceTreeMimicker
     */
    protected $mirror;

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
        if ($file->getExtension() !== 'twig') {
            return false;
        }

        $meta = $this->getMeta($file);
        if (!$meta) {
            return false;
        }

        return true;
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
     * Get the Twig meta (a comment starting with meta)
     *
     * @param  \SplFileInfo $file the Twig file
     *
     * @return array
     */
    protected function getMeta(\SplFileInfo $file)
    {
        $contents = file_get_contents($file);

        preg_match('/\{# meta(.+)\n#\}/s', $contents, $matches);

        if (!isset($matches[1])) {
            return null;
        }

        $meta = Yaml::parse($matches[1]);

        return $meta;
    }

    /**
     * Remove Twig extension from path
     *
     * @param  string $path
     *
     * @return string
     */
    protected function trimTwigExtension($path)
    {
        return substr($path, 0, -5);
    }
}
