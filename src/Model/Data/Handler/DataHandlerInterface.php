<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Handler;

/**
 * Build config for a file or directory
 */
interface DataHandlerInterface
{
    /**
     * check if the handler can handle the file/directory
     *
     * @param \SplFileInfo $file
     *
     * @return bool
     */
    public function apply(\SplFileInfo $file);

    /**
     * Return the value of the data node
     *
     * @param \SplFileInfo $file
     *
     * @return array|scalar
     */
    public function getValue(\SplFileInfo $file);
}
