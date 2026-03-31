<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use Nayleen\Async\Application;
use Nayleen\Async\Bootstrap\Options;
use Nayleen\Async\Bootstrapper;
use Nayleen\Async\Kernel;
use Symfony\Component\Console\Application as SymfonyConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tempest\Discovery\DiscoveryLocation;

final readonly class Console implements Application
{
    public function __construct(private Kernel $kernel) {}

    /**
     * @param list<DiscoveryLocation> $discoveryLocations
     */
    public static function boot(
        ?string $projectRoot = null,
        array $discoveryLocations = [],
    ): self {
        assert($projectRoot !== '');

        $_ENV['KERNEL_LIFECYCLE_LOGS'] = $_SERVER['KERNEL_LIFECYCLE_LOGS'] = 0;

        $kernel = Bootstrapper::run(Options::create(
            $projectRoot,
            $discoveryLocations,
        ));

        return new self($kernel);
    }

    public function run(): int
    {
        $exitCode = $this->kernel->run(static fn (
            SymfonyConsole $console,
            InputInterface $input,
            OutputInterface $output,
        ): int => $console->run($input, $output));

        assert(is_int($exitCode));

        return $exitCode;
    }
}
