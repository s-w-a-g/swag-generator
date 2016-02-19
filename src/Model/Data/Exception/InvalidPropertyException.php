<?php

/*
 * @Author: Woecifaun
 * @License: view License file if any
 */

namespace Swag\Model\Data\Exception;

/**
 * Exception thrown went attempting to get invalid data property
 */
class InvalidPropertyException extends \Exception
{
    /**
     * file that cannot be turned in a user data node
     *
     * @var string
     */
    private $invalidProperty;

    /**
     * construct
     *
     * @param string $invalidProperty the invalid file
     */
    public function __construct($invalidProperty)
    {
        $this->invalidProperty = $invalidProperty;

        $this->message = sprintf(
            'Cannot find a property [%s] in user data.',
            $invalidProperty
        );
    }

    /**
     * Get the invalid data file
     *
     * @return string
     */
    public function getInvalidProperty()
    {
        return $this->invalidProperty;
    }
}
