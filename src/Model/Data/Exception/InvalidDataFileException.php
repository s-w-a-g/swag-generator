<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Exception;

/**
 * Invalid file for building a data node exception
 */
class InvalidDataFileException extends \Exception
{
    /**
     * file that cannot be turned in a user data node
     *
     * @var \SplFileInfo
     */
    private $invalidFile;

    /**
     * construct
     *
     * @param \SplFileInfo $invalidFile the invalid file
     */
    public function __construct(\SplFileInfo $invalidFile)
    {
        $this->invalidFile = $invalidFile;

        $this->message = sprintf(
            'Cannot find a Data type for file: %s',
            $invalidFile
        );
    }

    /**
     * Get the invalid data file
     *
     * @return \SplFileInfo
     */
    public function getInvalidDataFile()
    {
        return $this->invalidFile;
    }
}
