<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Framework\Receiver;
use Framework\RegisterConfigs;
use Framework\RegisterRoutes;
use Framework\Process;
use Framework\Invoker;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$request = Request::createFromGlobals();
$containerBuilder = new ContainerBuilder();

Framework\Registry::addContainer($containerBuilder);

$receiver = new Receiver($request, $containerBuilder);
//$registerConfigs = new RegisterConfigs($receiver);
$registerRoutes = new RegisterRoutes($receiver);
$process = new Process($receiver);

$invoker = new Invoker();
//$response = $invoker->action($registerConfigs);
$response = $invoker->action($registerRoutes);
$response = $invoker->action($process);

$response->send();

// $response = (new Kernel($containerBuilder))->handle($request);
// $response->send();