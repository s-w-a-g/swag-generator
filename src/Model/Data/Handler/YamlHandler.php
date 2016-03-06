<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Handler;

use Symfony\Component\Yaml\Yaml;

/**
 * User Data as Yaml file
 */
class YamlHandler implements DataHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(\SplFileInfo $file)
    {
        return in_array($file->getExtension(), ['yml', 'yaml']);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(\SplFileInfo $file)
    {
        return Yaml::parse(file_get_contents($file));
    }
}
