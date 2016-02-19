<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

use Swag\Exception\InvalidPageException;
use Swag\Model\Data\Exception\InvalidPropertyException;
use Swag\Model\Page\Exception\InvalidTwigMetaException;
use Swag\Model\Page\PageHandlerInterface;
use Swag\Service\SourceTreeMimicker;
use Symfony\Component\Yaml\Yaml;

/**
 * Renders twig templates to pages
 */
class IterativeTwigHandler implements PageHandlerInterface
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
        if ($file->getExtension() !== 'twig') {
            return false;
        }

        $meta = $this->getMeta($file);
        if (!$meta) {
            return false;
        }

        if (isset($meta['type']) && $meta['type'] === 'iterative') {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function processFile(\SplFileInfo $file, $data = null)
    {
        $meta  = $this->getMeta($file);
        $items = $this->getDataValue($data, $meta['data']);

        if (!isset($meta['item'])) {
            throw new InvalidTwigMetaException(sprintf(
                'Invalid meta for file "%s". The meta must contain an "item" entry for an iterative page. ',
                $file
            ));
        }
        $itemName = $meta['item'];

        if (isset($data[$itemName])) {
            throw new InvalidTwigMetaException(sprintf(
                'The meta value for "item": %s collides with the data property [%s]',
                $itemName,
                $itemName
            ));
        }

        foreach ($items as $key => $item) {
            $data[$itemName] = $item;

            $relativePath = $this->mirror->getSrcFileRelativePath($file);
            $destination  = dirname($relativePath).'/'.$key.'.html';
            $destination  = $this->mirror->generateDestinationPathName($destination);
            $this->mirror->ensureDestinationDirectoryIsWritable($destination);

            echo "\nRendering ".$relativePath." for ".$key;
            $content = $this->twig->render($relativePath, $data);
            file_put_contents($destination, $content);
        }
    }

    /**
     * Get the Twig meta (a comment starting with meta)
     *
     * @param \SplFileInfo $file the Twig file
     *
     * @return array
     */
    private function getMeta(\SplFileInfo $file)
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
     * @param string $path
     *
     * @return string
     */
    private function trimTwigExtension($path)
    {
        return substr($path, 0, -5);
    }

    /**
     * Return the value matching the path in data
     *
     * @param array  $data
     * @param string $path
     *
     * @return array|scalar
     */
    private function getDataValue($data, $path)
    {
        $steps = explode('.', $path);
        $value = $data;

        foreach ($steps as $step) {
            if (isset($value[$step])) {
                $value = $value[$step];
            } else {
                throw new InvalidPropertyException($path);
            }
        }

        return $value;
    }
}
