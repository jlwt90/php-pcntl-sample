<?php

/**
 * This script illustrates how PDO is being copied during `pcntl_fork` process.
 */
require_once 'vendor/autoload.php';

use Codedungeon\PHPCliColors\Color;
use Jlwt90\Writer\ConsoleWriter;

$writer = new ConsoleWriter();
$writer->write("Hello World! This is PDO test");

// init PDO
$userName = "x";
$userPass = "x";
$dbName = "x";

$dsn = "mysql:dbname={$dbName};host=127.0.0.1";
$pdo = new PDO($dsn, $userName, $userPass);
$writer->write("connected to DB successfully");

$writer->write("Now we will fork the process");

// fork the process now
$pid = pcntl_fork();
if ($pid == -1) {
    $writer->error("cannot fork");
    die();
} else if ($pid) {
    $writer->write("This is from parent - I do nothing");
} else {
    $writer->write("This is from child");
}

pcntl_signal(SIGTERM, "handleSignal");

// run something from both parent & child processes.
if (!$pid) {
    $pdo = new PDO($dsn, $userName, $userPass);
    $n = 0;
    while ($n < 10) {
        pcntl_signal_dispatch();
        $stmt = $pdo->prepare("SELECT 1");
        $res = $stmt->execute();
        if ($res) {
            $writer->write("DB returns correct value: " . $res);
        }
        sleep(1);
        $n++;
    }
}

if ($pid) {
    $statusCode = 0;
    $res = pcntl_wait($statusCode);
    $writer->write("Child process has been finished! Now I will connect to DB again...");
    try {
        $stmt = $pdo->prepare("SELECT 1");
        $res = $stmt->execute();
    } catch (PDOException $e) {
        $writer->error("Interesting! This PDO call will fail :P - ERROR MSG: " . $e->getMessage());
    }
    echo Color::BG_CYAN, "[{$pid}] Bye Bye :)", Color::RESET, PHP_EOL;
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