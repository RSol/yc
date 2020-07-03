<?php

use app\helpers\ArgumentsHelper;
use app\helpers\DoublesHelper;
use app\helpers\report\EchoReporter;
use app\helpers\report\ReporterInterface;
use app\helpers\report\TxtFileReporter;

include __DIR__ . '/../vendor/autoload.php';

$argumentsHelper = new ArgumentsHelper($argv);
$path = $argumentsHelper->getFirstArgument();

$reporters = [
    'txt' => TxtFileReporter::class,
    'echo' => EchoReporter::class,
];

$reportFormat = $argumentsHelper->getArgumentByPosition(2);
$reporterClass = ($reportFormat && array_key_exists($reportFormat, $reporters))
    ? $reporters[$reportFormat]
    : $reporters['txt'];

/** @var ReporterInterface $reporter */
$reporter = new $reporterClass;


$instance = new DoublesHelper();
$instance->findDoubles($path);
$instance->report($reporter);
