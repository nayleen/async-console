<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use Nayleen\Async\AbstractComponent;
use Nayleen\Async\DI\Loader;

final readonly class ConsoleComponent extends AbstractComponent
{
    public function register(Loader $loader): void
    {
        $loader->loadDir(dirname(__DIR__) . '/config');
    }
}
