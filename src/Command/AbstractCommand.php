<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Audi\AudiSystem\Command;

use Symfony\Component\Console\Command\Command;
use Silex\Application;
use Audi\AudiSystem\Traits\ContainerTrait;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand extends Command
{
    use ContainerTrait;

    /**
     * @param Application $app
     */
    public function __construct(Application $app = null, $name = null)
    {
        if (null !== $app) {
            $this->setContainer($app);
        }
        parent::__construct($name);
    }
}
