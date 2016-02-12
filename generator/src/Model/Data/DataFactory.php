<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

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
            return new DataCluster($file);
        }

        switch ($file->getExtension()) {
            case 'yml':
            case 'yaml':
                return new DataYaml($file);
        }

        throw new InvalidDataFileException($file);
    }
}
