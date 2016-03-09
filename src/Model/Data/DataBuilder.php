<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

use Swag\Model\Data\Exception\InvalidDataFileException;
use Swag\Model\Data\Handler\DataHandlerInterface;
use Swag\Service\Notifier;

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
     * Console output handler
     *
     * @var Notifier
     */
    private $notifier;

    /**
     * Construct
     *
     * @param \SplFileInfo $dataLocation
     * @param Notifier     $notifier
     */
    public function __construct(\SplFileInfo $dataLocation, Notifier $notifier)
    {
        $this->dataLocation = $dataLocation;
        $this->notifier     = $notifier;
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
            $this->writeLine($e->getMessage());
            continue;
        }

        return $handler->getValue($this->dataLocation);
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

    /**
     * Write a line on the console stdOut
     *
     * @param  string $line
     */
    public function writeLine($line)
    {
        $this->notifier->writeLine($line);
    }
}
