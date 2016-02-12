<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Service;

/**
 * Renders twig templates to pages
 */
class PageRenderer
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
     * Render a Twig template and move it to the website directory
     *
     * @param \SplFileInfo $file Twig source file
     * @param array        $data user variables for template engine
     */
    public function render(\SplFileInfo $file, array $data)
    {
        echo 'rendering '.$file;

        $relativePath = $this->mirror->getSrcFileRelativePath($file);
        $destination  = $this->mirror->generateDestinationPathName($this->trimTwigExtension($relativePath));
        $this->mirror->ensureDestinationDirectoryIsWritable($destination);

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
