<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 07.08.14
 * Time: 0:01
 * File: main.php
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = include __DIR__.'/../vendor/autoload.php';
$loader->setPsr4('Modules\\', __DIR__.'/Modules');


$config_path = '';
$slim = new \Slim\Slim();
$app = new \SlimController\Controller($slim);

// map http route
$app->mapRoute('/:a/:b/', function($a, $b) { var_dump($a, $b); });

// console command binding example
$app->mapCommand('/move/:uid/:to/', function ($uid, $to) { var_dump($uid, $to); });
$app->mapCommand('/set/:aaa/', "main::main");

$app->registerModuleDispatcherCallback(function($module_name) {

	$class_name = sprintf('\Modules\%s\Module%s', ucfirst($module_name), ucfirst($module_name));
	return new $class_name($this);

});

$app->run();