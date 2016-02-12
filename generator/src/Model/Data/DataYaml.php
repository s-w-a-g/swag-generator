<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data;

use Symfony\Component\Yaml\Yaml;

/**
 * User Data as Yaml file
 */
class DataYaml extends AbstractData
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return Yaml::parse(file_get_contents($this->node));
    }
}
