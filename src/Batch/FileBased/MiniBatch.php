<?php

require_once 'vendor/autoload.php';

// mark it as running
$name = $argv[1];
$runFile = "./out/{$name}.out.running";
$data = print_r($argv, true);
file_put_contents($runFile, $data, FILE_APPEND | LOCK_EX);

// parse argument & calculate x^2
$inputs = explode(',', $argv[2]);
$res = [];
foreach ($inputs as $i) {
    $res[] = $i * $i;
    sleep(1);
}

// output
$out = implode(',', $res);
$doneFile = "./out/{$name}.out";
file_put_contents($doneFile, $out, FILE_APPEND | LOCK_EX);

// remove running file
unlink($runFile);