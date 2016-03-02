<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Handler;

use Swag\Model\Data\DataFactory;
use Swag\Model\Data\Exception\InvalidDataFileException;
use Swag\Model\Data\Exception\InvalidStructureException;

/**
 * Build config for a file or directory
 */
class ClusterHandler extends AbstractDataHandler
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $value = [];
        $tree  = new \FilesystemIterator($this->node, \FilesystemIterator::SKIP_DOTS);

        foreach ($tree as $file) {

            try {
                $node = DataFactory::create($file);
            } catch (InvalidDataFileException $e) {
                printf(
                    "Skipping invalid file: %s\n",
                    $e->getInvalidDataFile()
                );

                continue;
            }

            if ($node->isValid()) {
                $key = $node->getKey();
                if (isset($value[$key])) {
                    throw new InvalidStructureException($key);
                }
                $value[$key] = $node->getValue();
            }
        }

        return $value;
    }
}
