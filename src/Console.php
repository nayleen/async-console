<?php

declare(strict_types = 1);

namespace Nayleen\Async;

use Nayleen\Async\Application as App;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Console extends App
{
    /**
     * @param non-empty-string|null $defaultCommand
     */
    public function __construct(
        private readonly ?string $defaultCommand = null,
        Tasks $tasks = new Tasks(),
        private readonly ?InputInterface $input = null,
        private readonly ?OutputInterface $output = null,
    ) {
        assert($this->defaultCommand !== '');
        parent::__construct($tasks);
    }

    protected function execute(Kernel $kernel): int
    {
        $console = $kernel->container()->get(Application::class);
        $console->setAutoExit(false);

        if (isset($this->defaultCommand)) {
            assert($this->defaultCommand !== '');
            $console->setDefaultCommand($this->defaultCommand, true);
        }

        return $console->run(
            $this->input ?? $kernel->container()->get(InputInterface::class),
            $this->output ?? $kernel->container()->get(OutputInterface::class),
        );
    }
}
