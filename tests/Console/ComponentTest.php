<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use Nayleen\Async\Component\TestCase;
use Nayleen\Async\Console\Command\Loader as CommandLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

/**
 * @internal
 */
final class ComponentTest extends TestCase
{
    protected function component(): Component
    {
        return new Component();
    }

    /**
     * @test
     */
    public function registers_console(): void
    {
        $this->assertContainerHasService(Application::class);
        $this->assertContainerHasService(CommandLoaderInterface::class, CommandLoader::class);

        $console = $this->container()->get(Application::class);
        self::assertSame('Kernel Console', $console->getName());
    }
}
