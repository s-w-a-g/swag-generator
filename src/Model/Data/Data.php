<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

/**
 * Data node class
 *
 * All properties will be public except for $privateContent
 * $privateContent contains the content that is gave back on __toString() call
 */
class Data
{
    /**
     * Contains the real content i.e. a converted markdown file
     *
     * @var string
     */
    private $privateContent;

    /**
     * Set content of the Data as opposed to meta that are public
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->privateContent = $content;
    }

    /**
     * Return the real content
     *
     * @return string
     */
    public function __toString()
    {
        return $this->privateContent;
    }
}
