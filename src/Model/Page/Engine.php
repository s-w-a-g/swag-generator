<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

use Swag\Model\Page\Exception\InvalidPageException;
use Swag\Model\Page\Handler\PageHandlerInterface;

/**
 * Renders twig templates to pages
 */
class Engine
{
    /**
     * Directory holding pages to proccess
     *
     * @var \SplFileInfo
     */
    private $pagesLocation;

    /**
     * data generated from user's data directory
     *
     * @var array
     */
    private $data = [];

    /**
     * list of pageHandler
     *
     * @var PageHandlerInterface[]
     */
    private $pageHandlers = [];

    /**
     * Construct
     *
     * @param \SplFileInfo $pagesLocation
     */
    public function __construct(\SplFileInfo $pagesLocation)
    {
        $this->pagesLocation = $pagesLocation;
    }

    /**
     * Add a PageHandler to the list
     *
     * @param PageHandlerInterface $pageHandler
     */
    public function addPageHandler(PageHandlerInterface $pageHandler)
    {
        $this->pageHandlers[] = $pageHandler;
    }

    /**
     * Browse source directory to process user files
     */
    public function processPages()
    {
        $sourceTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->pagesLocation, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($sourceTree as $file) {
            try {
                $handler = $this->getHandlerForFile($file);
            } catch (InvalidPageException $e) {
                echo $e->getMessage();
                continue;
            }

            $handler->processFile($file, $this->data);
        }
    }

    /**
     * set data generated from user's data directory
     *
     * @param array $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine the right handler for the file
     * First to match is the one
     *
     * @param \SplFileInfo $file
     *
     * @return PageHandlerInterface
     */
    private function getHandlerForFile(\SplFileInfo $file)
    {
        foreach ($this->pageHandlers as $handler) {
            if ($handler->apply($file)) {
                return $handler;
            }
        }

        throw new InvalidPageException(sprintf(
            "\nCouldn't find any handler for %s",
            $file
        ));
    }
}
