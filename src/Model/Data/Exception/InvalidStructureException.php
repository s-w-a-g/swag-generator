<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Exception;

use Swag\Exception\SwagException;

/**
 * Invalid file for building a data node exception
 */
class InvalidStructureException extends SwagException
{
    /**
     * construct
     *
     * @param string $duplicateKey the filename being redundant
     */
    public function __construct($duplicateKey)
    {
        $this->message = sprintf(
            'Two or more data files/directory share the same basename : "%s"',
            $duplicateKey
        );
    }
}
