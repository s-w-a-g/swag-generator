<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page\Handler;

use Swag\Model\Data\Exception\InvalidPropertyException;
use Swag\Model\Page\Exception\InvalidTwigMetaException;

/**
 * Renders twig templates to pages
 */
class IterativeTwigHandler extends TwigHandler
{
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

            $relativePath = $this->fileSystem->getSrcFileRelativePath($file);
            $destination  = dirname($relativePath).'/'.$key.'.html';
            $destination  = $this->fileSystem->generateDestinationPathName($destination);
            $this->fileSystem->ensureDestinationDirectoryIsWritable($relativePath);

            echo "\nRendering ".$relativePath." for ".$key;
            $content = $this->twig->render($relativePath, $data);
            file_put_contents($destination, $content);
        }
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
