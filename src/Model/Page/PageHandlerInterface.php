<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Page;

/**
 * Renders twig templates to pages
 */
interface PageHandlerInterface
{
    /**
     * Check if it should handle the specified file
     *
     * @param  \SplFileInfo $file The user file to handle
     * @return bool
     */
    public function apply(\SplFileInfo $file);

    /**
     * Generate the file content depending to the destination directory
     *
     * @param  \SplFileInfo $file The user source file to process
     * @param  array        $data The user data prepped for file processing
     */
    public function processFile(\SplFileInfo $file, $data);
}
