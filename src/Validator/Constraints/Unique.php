<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Constraint for the unique document validator.
 *
 * @Annotation
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 */
class Unique extends UniqueEntity
{
    public $service = 'doctrine_odm.mongodb.unique';
}
