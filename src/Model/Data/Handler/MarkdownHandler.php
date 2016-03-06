<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Handler;

use Michelf\MarkdownExtra;
use Swag\Model\Data\Data;
use Symfony\Component\Yaml\Yaml;

/**
 * User Data as Yaml file
 */
class MarkdownHandler implements DataHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(\SplFileInfo $file)
    {
        return in_array($file->getExtension(), ['md', 'mdown']);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(\SplFileInfo $file)
    {
        $data     = new Data();
        $start    = 0;
        $contents = file_get_contents($file);
        preg_match('/^[-]{3}(.+?)(?:\n---\n)/s', $contents, $matches);
        if (isset($matches[1])) {
            $start = strlen($matches[0]);
            $metas = Yaml::parse($matches[1]);
            foreach ($metas as $meta => $value) {
                $data->{$meta} = $value;
            }
        }

        $data->setContent(MarkdownExtra::defaultTransform(substr($contents, $start)));

        return $data;
    }
}
