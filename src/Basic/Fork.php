<?php

/**
 * This file demonstrate how pcntl functions work.
 * @link https://www.php.net/manual/en/pcntl.example.php
 *
 * In this example, we share the code with main process and fork process.
 */
require_once 'vendor/autoload.php';

// This line is important!!
declare(ticks=1);

// startup
use Codedungeon\PHPCliColors\Color;
use Jlwt90\Writer\ConsoleWriter;

$writer = new ConsoleWriter();
$writer->write("Hello World! This is the basic usage of pcntl");

// fork the process now
$pid = pcntl_fork();
if ($pid == -1) {
    $writer->error("cannot fork");
    die();
} else if ($pid) {
    $writer->write("This is from parent");
} else {
    $writer->write("This is from child");
}

pcntl_signal(SIGTERM, "handleSignal");

// run something from both parent & child processes.
$n = 0;
while ($n < 50) {
    // parent process will skip counting if $n is an odd number.
    if (!$pid || $n%2 != 0) {
        $writer->write("Let's count! {$n}");
    }
    sleep(1);
    $n++;
}

function handleSignal($sigNo)
{
    switch ($sigNo) {
        case SIGTERM:
            // e.g. kill -s TERM 1234 or when k8s kills a pod
            $pid = getmypid();
            echo Color::BG_CYAN, "[{$pid}] Oh I have to say goodbye :(", Color::RESET, PHP_EOL;
            exit();
        default:
            echo "received signal: {$sigNo}";
    }
}