<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

use Swag\Model\Data\Exception\InvalidDataFileException;
use Swag\Model\Data\Handler\DataHandlerInterface;

/**
 * Build data upon user's data files
 */
class DataBuilder
{
    /**
     * Directory holding data files to process
     *
     * @var \SplFileInfo
     */
    private $dataLocation;

    /**
     * list of dataHandler
     *
     * @var DataHandlerInterface[]
     */
    private $dataHandlers = [];

    /**
     * Construct
     *
     * @param \SplFileInfo $dataLocation
     */
    public function __construct(\SplFileInfo $dataLocation)
    {
        $this->dataLocation = $dataLocation;
    }

    /**
     * Add a DataHandler to the list
     *
     * @param DataHandlerInterface $dataHandler
     */
    public function addDataHandler(DataHandlerInterface $dataHandler)
    {
        $this->dataHandlers[] = $dataHandler;
        if (method_exists($dataHandler, 'setDataBuilder')) {
            $dataHandler->setDataBuilder($this);
        }
    }

    /**
     * Browse source directory to process user files
     *
     * @return array
     */
    public function processData()
    {
        try {
            $handler = $this->getHandlerForFile($this->dataLocation);
        } catch (InvalidPageException $e) {
            echo $e->getMessage();
            continue;
        }

        $data = $handler->getValue($this->dataLocation);

        return $data;
    }

    /**
     * Determine the right handler for the file
     * First to match is the one
     *
     * @param \SplFileInfo $file
     *
     * @return PageHandlerInterface
     */
    public function getHandlerForFile(\SplFileInfo $file)
    {
        foreach ($this->dataHandlers as $handler) {
            if ($handler->apply($file)) {
                return $handler;
            }
        }

        throw new InvalidDataFileException($file);
    }
}
