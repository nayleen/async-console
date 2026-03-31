<?php

declare(strict_types = 1);

use Amp\ByteStream\WritableStream;
use Nayleen\Async\Console\StreamOutput;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as SymfonyConsole;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return [
    // console parameters
    'console.name' => DI\env('CONSOLE_NAME', DI\string('{app.name} Console')),

    // console services
    SymfonyConsole::class => DI\factory(static function (ContainerInterface $c): SymfonyConsole {
        $name = $c->get('console.name');
        $version = $c->get('app.version');

        assert(is_string($name));
        assert(is_string($version));

        $console = new SymfonyConsole($name, $version);
        $console->setAutoExit(false);

        return $console;
    }),

    InputInterface::class => DI\factory(static fn () => new ArgvInput()),

    StreamOutput::class => DI\factory(static function (ContainerInterface $c): StreamOutput {
        $stdout = $c->get('app.stdout');
        assert($stdout instanceof WritableStream);

        return new StreamOutput($stdout);
    }),

    OutputInterface::class => DI\get(StreamOutput::class),
];
