<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

require_once __DIR__.'/generator/bootstrap.php';

foreach ($srcTree as $file)
{
    if (!$file->isFile()) {
        continue;
    }

    if (in_array($file->getExtension(), $config['ignored_files'])) {
        continue;
    }

    if ($file->getExtension() !== 'twig') {
        $copier->copy($file);
        continue;
    }

    $renderer->render($file);
}
