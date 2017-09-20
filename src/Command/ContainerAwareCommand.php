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

use LogicException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Command.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class ContainerAwareCommand extends Command
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @return ContainerInterface
     *
     * @throws LogicException
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            $application = $this->getApplication();
            if (null === $application) {
                throw new LogicException('The container cannot be retrieved as the application instance is not yet set.');
            }

            $this->container = $application->getKernel()->getContainer();
        }

        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
