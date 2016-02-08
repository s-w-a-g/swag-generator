<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

require_once __DIR__.'/generator/bootstrap.php';

use \Heartsentwined\FileSystemManager\FileSystemManager;

foreach (FileSystemManager::fileIterator('src') as $filePath) {
var_dump($filePath);
    // $file = new \SplFileInfo(__DIR__.'/'.$filePath);

    // if (!$file->isFile()) {
    //     continue;
    // }

    // if ($file->getExtension() !== 'twig') {
    //     $copier->copy($file);
    //     continue;
    // }

    // $renderer->render($file);
}
