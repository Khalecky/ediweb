<?php

require_once "vendor/autoload.php";

$fileName = $argv[1] ?? "";
$report = \App\App::create($fileName)->getReport();
if ($report) {
    echo $report . PHP_EOL;
}
