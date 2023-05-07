<?php

namespace Jlwt90\Writer;

use Codedungeon\PHPCliColors\Color;

/**
 * ConsoleWriter writes log to STDOUT with PID prefix with newline character at the end.
 */
class ConsoleWriter implements Writer
{
    private int $count = 0;

    function write(string $msg, bool $newline = true): void
    {
        echo $this->formatMessage($msg, $newline);
        $this->count++;
    }

    function error(string $msg): void
    {
        echo Color::RED, $this->formatMessage($msg), Color::RESET, PHP_EOL;
        $this->count++;
    }

    function formatMessage(string $msg, bool $newline = true): string
    {
        $pid = getmypid();
        $trailing = $newline? "\n" : "";
        return "[{$pid}][{$this->count}] {$msg}{$trailing}";
    }
}