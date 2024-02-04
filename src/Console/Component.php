<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use DI;
use Nayleen\Async\Bootstrapper;
use Nayleen\Async\Component as BaseComponent;
use Nayleen\Async\Component\HasDependencies;

readonly class Component extends BaseComponent implements HasDependencies
{
    public static function dependencies(): iterable
    {
        yield Bootstrapper::class;
    }

    public function register(DI\ContainerBuilder $containerBuilder): void
    {
        $this->load($containerBuilder, dirname(__DIR__, 2) . '/config/console.php');
    }
}
