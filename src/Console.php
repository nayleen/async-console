<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use Amp\Future;
use Nayleen\Async\Runtime;
use Nayleen\Async\Runtime\Options;
use Symfony\Component\Console\Application as SymfonyConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Console extends Runtime
{
    /**
     * @inheritDoc
     */
    protected function setup(Options $context): Future
    {
        // @phpstan-ignore-next-line return.type
        return $this->kernel->submit(static fn (
            SymfonyConsole $console,
            InputInterface $input,
            OutputInterface $output,
        ): int => $console->run($input, $output));
    }
}
