<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

use Swag\Service\SourceTreeMimicker;

class SourceTreeMimickerTest extends PHPUnit_Framework_TestCase
{
    const USER_DIR        = 'tmp-user-directory';
    const SWAG_DIR        = 'swag';
    const PAGES_DIR       = 'pages';
    const DATA_DIR        = 'data';
    const DESTINATION_DIR = 'static_website';

    public function setUp()
    {
        // Method of creating filesystem for real was chosen
        // vfsStream misses the \SplFileInfo::getRealPath() feature

        // Container directory
        $userDir = __DIR__.'/../'.self::USER_DIR;
        exec('rm -rf '.$userDir);
        mkdir($userDir);
        $this->userDir = new \SplFileInfo(realpath($userDir));

        $swagDir = $this->userDir.DIRECTORY_SEPARATOR.self::SWAG_DIR;
        mkdir($swagDir);
        $this->swagDir = new \SplFileInfo(realpath($swagDir));

        // subdirectories
        $directories = [
            'pages'       => self::PAGES_DIR,
            'data'        => self::DATA_DIR,
            'destination' => self::DESTINATION_DIR
        ];
        foreach ($directories as $name => $dir) {
            $dir = $this->userDir.DIRECTORY_SEPARATOR.$dir;
            mkdir($dir);
            $this->$name = new \SplFileInfo(realpath($dir));
        }

    }

    public function tearDown()
    {
        exec('rm -rf '.$this->userDir);
    }

    public function testPathGeneration()
    {
        $relativePath = 'subdir/file.txt';
        $file     = new \SplFileInfo($this->swagDir.DIRECTORY_SEPARATOR.$relativePath);
        $mimicker = new SourceTreeMimicker($this->swagDir, $this->destination);

        $destinationPath = $mimicker->getSrcFileRelativePath($file);
        $this->assertTrue($destinationPath === $relativePath);

        $absolutePath = $mimicker->generateDestinationPathName($destinationPath);
        $testPath     = $this->destination.DIRECTORY_SEPARATOR.$relativePath;
        $this->assertTrue($absolutePath === $testPath);
    }

    public function testDestinationIsWritable()
    {
        # code...
    }
}
