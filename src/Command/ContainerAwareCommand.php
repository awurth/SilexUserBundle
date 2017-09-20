<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\SilexUser\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Command.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
abstract class ContainerAwareCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param string|null        $name
     */
    public function __construct(ContainerInterface $container, $name = null)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    /**
     * Gets the container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
