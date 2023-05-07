<?php

/**
 * This is the script to demonstrate how can we spawn our batch into mini-batch.
 */

use Codedungeon\PHPCliColors\Color;
use Jlwt90\Workload\NumberGenerator;
use Jlwt90\Workload\Workload;
use Jlwt90\Writer\ConsoleWriter;

require_once 'vendor/autoload.php';

$writer = new ConsoleWriter();

$gen = new NumberGenerator(1, 50);
$workloads = $gen->generateWorkload(10);

// execute mini-batch
$startTime = time();
foreach ($workloads as $w) {
    executeMiniBatch($w);
}

// watch until all output files are created
$waitLimit = 60;
foreach ($workloads as $w) {
    $fileName = "./out/{$w->name}.out";
    $res = watchOutputFiles($fileName, $waitLimit);
    if (!$res) {
        $writer->error("{$fileName} is not created" );
        exit(2);
    }
}

// read the outputs
foreach ($workloads as $w) {
    $fileName = "./out/{$w->name}.out";
    $out = file_get_contents($fileName);
    echo "{$fileName}: {$out}" . PHP_EOL;
    unlink($fileName);
}

$duration = time() - $startTime;
echo Color::BG_CYAN, "Everything works perfectly in {$duration}s :)", Color::RESET, PHP_EOL;

/**
 * Execute mini-batch in background without waiting for the response.
 * @param Workload $w
 * @return void
 */
function executeMiniBatch(Workload $w): void
{
    $name = $w->name;
    $arg = implode(',', $w->data);
    shell_exec("php ./src/Batch/FileBased/MiniBatch.php {$name} {$arg} > /dev/null 2>&1 &");
}

function watchOutputFiles(string $fileName, int $waitLimitInSec): bool
{
    while ($waitLimitInSec > 0) {
        if (file_exists($fileName)) {
            return true;
        }
        sleep(1);
        $waitLimitInSec--;
    }
    return false;
}
