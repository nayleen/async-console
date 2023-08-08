<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use DI;
use Nayleen\Async\Component as BaseComponent;

class Component extends BaseComponent
{
    public function name(): string
    {
        return self::class;
    }

    public function register(DI\ContainerBuilder $containerBuilder): void
    {
        $this->load($containerBuilder, dirname(__DIR__, 2) . '/config/console.php');
    }
}
