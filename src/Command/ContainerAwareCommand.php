<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Command;

use Pimple\Container;
use Symfony\Component\Console\Command\Command;

/**
 * Command.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
abstract class ContainerAwareCommand extends Command
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Container $container
     * @param string|null        $name
     */
    public function __construct(Container $container, $name = null)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    /**
     * Gets the container.
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
