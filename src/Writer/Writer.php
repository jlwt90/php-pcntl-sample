<?php

namespace Jlwt90\Writer;

interface Writer
{
    /**
     * Write message to the destination
     * @param string $msg log message
     * @param bool $newline true if newline character should be appended at the end of the line.
     * @return void
     */
    function write(string $msg, bool $newline = true): void;

    /**
     * Write error message to the destination
     * @param string $msg log message
     * @return void
     */
    function error(string $msg): void;
}