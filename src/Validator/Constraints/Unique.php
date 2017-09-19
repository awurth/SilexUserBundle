<?php

namespace AWurth\SilexUser\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Constraint for the unique document validator.
 *
 * @Annotation
 */
class Unique extends UniqueEntity
{
    public $service = 'doctrine_odm.mongodb.unique';
}
