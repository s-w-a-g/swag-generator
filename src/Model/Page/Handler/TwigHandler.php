<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page\Handler;

use Swag\Model\Page\Handler\PageHandlerInterface;
use Swag\Model\FileSystem\FileSystem;
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
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * Construct
     *
     * @param \Twig_Environment $twig
     * @param FileSystem        $fileSystem
     */
    public function __construct(\Twig_Environment $twig, FileSystem $fileSystem)
    {
        $this->twig       = $twig;
        $this->fileSystem = $fileSystem;
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
        if (empty($meta['generate'])) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function processFile(\SplFileInfo $file, $data = null)
    {

        $relativePath = $this->fileSystem->getSrcFileRelativePath($file);
        $destination  = $this->fileSystem->generateDestinationPathName($this->trimTwigExtension($relativePath));
        $this->fileSystem->ensureDestinationDirectoryIsWritable($relativePath);

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

        preg_match('/\{# meta(.+?)\n#\}/s', $contents, $matches);

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
