<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

use Swag\Model\Data\Data;

class DataTest extends PHPUnit_Framework_TestCase
{
    public function testSetContent()
    {
        $string = 'String to compare';
        $data = new Data();
        $data->setContent($string);

        $this->assertEquals($string, (string) $data);
    }
}
