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

$slim = new \Slim\Slim();
$app = new \SlimController\Controller($slim);


# setup WEB advanced
$app->setup(
	function(\SlimController\Controller $ctrl)
	{
		$ctrl->Slim()->config('debug', true);
		$ctrl->Slim()->add(new \SlimController\wrapper\Slim\middleware\GZipOutput());
	},
	\SlimController\Controller::MODE_WEB
);


# setup CLI advanced
$app->setup(
	function(\SlimController\Controller $ctrl)
	{
		var_dump('IN THE CONSOLE');
	},
	\SlimController\Controller::MODE_CLI
);



// map http route
$app->add_route('/test/:a/:b/', function($a, $b) { var_dump($a, $b); });

// console command binding example
$app->add_command('/move/:uid/:to/', function ($uid, $to) { var_dump($uid, $to); });
$app->add_command('/set/:aaa/', "main::main");

/**
 * Формирование модуля
 * По сути, тут и определяется как и где лежат модули
 */
$app->registerModuleDispatcherCallback(function($module_name, $controller) {
	$class_name = sprintf('\Modules\%s\Module%s', ucfirst($module_name), ucfirst($module_name));
	return new $class_name($controller);
});


// запуск приложения
// определение консольный запуск или же из под апача
$mode = (!isset($_SERVER['HTTP_HOST'])) ?
	\SlimController\Controller::MODE_CLI :
	\SlimController\Controller::MODE_WEB;

$app->run($mode);