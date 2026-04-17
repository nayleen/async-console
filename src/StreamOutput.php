<?php

declare(strict_types = 1);

namespace Nayleen\Async\Console;

use Amp\ByteStream\WritableStream;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output;

use function Amp\Log\hasColorSupport;

final class StreamOutput extends Output
{
    public function __construct(
        private readonly WritableStream $sink,
        ?int $verbosity = self::VERBOSITY_NORMAL,
        ?bool $decorated = null,
        ?OutputFormatterInterface $formatter = null,
    ) {
        $decorated ??= $this->hasColorSupport();

        parent::__construct($verbosity, $decorated, $formatter);
    }

    private function hasColorSupport(): bool
    {
        // follow https://force-color.org/
        if (($_ENV['FORCE_COLOR'] ?? $_SERVER['FORCE_COLOR'] ?? null) !== null) {
            return true;
        }

        // follow https://no-color.org/
        if (($_ENV['NO_COLOR'] ?? $_SERVER['NO_COLOR'] ?? null) !== null) {
            return false;
        }

        return hasColorSupport();
    }

    protected function doWrite(string $message, bool $newline): void
    {
        if ($newline) {
            $message .= PHP_EOL;
        }

        $this->sink->write($message);
    }
}
