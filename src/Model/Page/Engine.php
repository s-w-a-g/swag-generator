<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

use Swag\Exception\InvalidPageException;
use Swag\Model\Page\PageHandlerInterface;

/**
 * Renders twig templates to pages
 */
class Engine
{
    /**
     * list of pageHandler
     *
     * @var PageHandlerInterface[]
     */
    private $pageHandlers = [];

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
     *
     * @param  \SplFileInfo $pagesLocation Location of user pages to process
     * @param  array        $data          User's data prepped for injecting in templates
     */
    public function processPages(\SplFileInfo $pagesLocation, $data)
    {
        $sourceTree = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($pagesLocation, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($sourceTree as $file) {
            try {
                $handler = $this->getHandlerForFile($file);
            } catch (InvalidPageException $e) {
                echo $e->getMessage();
                continue;
            }

            $handler->processFile($file, $data);
        }
    }

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
