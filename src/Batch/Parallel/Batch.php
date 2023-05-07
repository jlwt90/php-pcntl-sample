<?php

/**
 * This script demonstrate how `parallel` works in PHP8.
 */
require_once 'vendor/autoload.php';

use Codedungeon\PHPCliColors\Color;
use Jlwt90\Workload\NumberGenerator;
use Jlwt90\Writer\ConsoleWriter;
use parallel\Runtime;


$writer = new ConsoleWriter();

$gen = new NumberGenerator(1, 50);
$workloads = $gen->generateWorkload(3);

$startTime = time();
// execute mini-batch
$runtime = [];
foreach ($workloads as $w) {
    $runtime[$w->name] = new Runtime();
}
$futures = [];
foreach ($workloads as $w) {
    $data = $w->data;
    $r = $runtime[$w->name];
    $f = $r->run(function () use ($data) {
        $res = [];
        foreach ($data as $d) {
            $res[] = $d * $d;
            sleep(1);
        }
        return $res;
    });
    $futures[$w->name] = $f;
}

foreach ($futures as $k => $f) {
    $res = implode(',', $f->value());
    $writer->write("{$k}: {$res}");
}

foreach ($runtime as $r) {
    $r->close();
}

$duration = time() - $startTime;
echo Color::BG_CYAN, "Everything works perfectly in {$duration}s :)", Color::RESET, PHP_EOL;
