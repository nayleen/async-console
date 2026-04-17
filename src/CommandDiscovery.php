<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use DI;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

/**
 * @internal
 *
 * @phpstan-type DiscoveryItem = array{names: list<non-empty-string>, class: class-string, invokable: bool}
 */
final class CommandDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly Application $console,
        private readonly DI\Container $container,
    ) {}

    public function apply(): void
    {
        $container = $this->container;
        $factories = [];

        foreach ($this->discoveryItems as $item) {
            /** @var DiscoveryItem $item */
            ['names' => $names, 'class' => $class, 'invokable' => $invokable] = $item;

            foreach ($names as $name) {
                $factories[$name] = static function () use ($container, $name, $class, $invokable) {
                    if ($invokable) {
                        $code = $container->get($class);
                        assert(is_callable($code));

                        return new Command($name, $code);
                    }

                    $command = $container->get($class);
                    assert($command instanceof Command);

                    return $command;
                };
            }
        }

        $this->console->setCommandLoader(new FactoryCommandLoader($factories));
    }

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if (!$class->hasAttribute(AsCommand::class)) {
            return;
        }

        $asCommandAttribute = $class->getAttribute(AsCommand::class);
        assert($asCommandAttribute instanceof AsCommand);

        $this->discoveryItems->add($location, [
            'names' => explode('|', $asCommandAttribute->name),
            'class' => $class->getName(),
            'invokable' => !$class->getReflection()->isSubclassOf(Command::class),
        ]);
    }
}
