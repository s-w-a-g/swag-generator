<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

use Swag\Model\Data\Exception\InvalidDataFileException;

/**
 * Build config for a file or directory
 */
class DataFactory
{
    /**
     * Create a new AbstractData based on file type
     *
     * @param  \SplFileInfo $file
     * @throws InvalidDataFileException
     *
     * @return AbstractData
     */
    public static function create(\SplFileInfo $file)
    {
        if ($file->isDir()) {
            return new Handler\ClusterHandler($file);
        }

        switch ($file->getExtension()) {
            case 'yml':
            case 'yaml':
                return new Handler\YamlHandler($file);
        }

        switch ($file->getExtension()) {
            case 'md':
            case 'mdown':
                return new Handler\MarkdownHandler($file);
        }

        throw new InvalidDataFileException($file);
    }
}
