<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Service;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Notifier for command line
 */
class Notifier
{
    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * Construct
     *
     * @param OutputIntirface $consoleOutput
     */
    public function __construct(OutputInterface $consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    /**
     * Write a line on the console stdOut
     *
     * @param  string $line
     */
    public function writeLine($line)
    {
        $this->consoleOutput->writeln($line);
    }
}
