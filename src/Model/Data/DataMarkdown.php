<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

use \Michelf\MarkdownExtra;

/**
 * User Data as Yaml file
 */
class DataMarkdown extends AbstractData
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return MarkdownExtra::defaultTransform(file_get_contents($this->node));
    }
}
