<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

/**
 * Build config for a file or directory
 */
abstract class AbstractData
{
    /**
     * entry (file|directory) in the user data directory
     * @var \SplFileInfo
     */
    protected $node;

    /**
     * construct
     *
     * @param \SplFileInfo $node
     */
    public function __construct(\SplFileInfo $node)
    {
        $this->node = $node;
    }

    /**
     * return the key generated upon name of the file/node
     *
     * @return [type] [description]
     */
    public function getKey()
    {
        return $this->node->getBasename('.'.$this->node->getExtension());
    }

    /**
     * Return the value of the data node
     *
     * @return array|scalar
     */
    abstract public function getValue();

    /**
     * Check if the data is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        // not if hidden file
        if (strpos($this->node->getBasename(), '.') === 0) {
            return false;
        }

        return true;
    }
}
