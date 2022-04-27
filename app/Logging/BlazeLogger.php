<?php


namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class BlazeLogger
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $datetime = now()->format('Y-m-d H:i:s');
            $handler->setFormatter(new LineFormatter("[{$datetime}] %channel%.%level_name%: %message%" . PHP_EOL));
        }
    }
}
