<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Handler;

use Swag\Model\Data\Exception\InvalidDataFileException;
use Swag\Model\Data\Exception\InvalidStructureException;
use Swag\Model\Data\DataBuilder;

/**
 * Build config for a file or directory
 */
class DirectoryHandler implements DataHandlerInterface
{
    /**
     * entry (file|directory) in the user data directory
     * @var \SplFileInfo
     */
    protected $dataBuilder;

    /**
     * {@inheritdoc}
     */
    public function apply(\SplFileInfo $file)
    {
        return $file->isDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(\SplFileInfo $file)
    {
        $value = [];
        $tree  = new \FilesystemIterator($file, \FilesystemIterator::SKIP_DOTS);

        foreach ($tree as $file) {
            try {
                $handler = $this->dataBuilder->getHandlerForFile($file);
            } catch (InvalidDataFileException $e) {
                $this->dataBuilder->writeLine(sprintf(
                    "Skipping invalid file: %s\n",
                    $e->getInvalidDataFile()
                ));

                continue;
            }

            $key = $this->getKey($file);
            if (isset($value[$key])) {
                throw new InvalidStructureException($key);
            }
            $value[$key] = $handler->getValue($file);
        }

        return $value;
    }

    /**
     * Set owning data builder
     *
     * @param DataBuilder $dataBuilder
     *
     * @return boolean
     */
    public function setDataBuilder(DataBuilder $dataBuilder)
    {
        $this->dataBuilder = $dataBuilder;
    }

    /**
     * return the key generated upon name of the file/directory
     *
     * @param \SplFileInfo $file
     *
     * @return string
     */
    private function getKey(\SplFileInfo $file)
    {
        return $file->getBasename('.'.$file->getExtension());
    }
}
