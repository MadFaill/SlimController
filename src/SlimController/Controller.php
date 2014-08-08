<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 06.08.14
 * Time: 23:48
 * File: Controller.php
 * Package: SlimController
 *
 */
namespace SlimController;
use Slim\Slim;
use SlimController\wrapper\Slim\Router;

/**
 * Class        Controller
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 06.08.14
 * @since       06.08.14
 * @version     0.01
 * @package     SlimController
 */
class Controller
{
	const MODE_WEB = 'web';
	const MODE_CLI = 'cli';

	const CONSOLE_METHOD = 'CONSOLE';

	/**
	 * Суть в неком HMVC-подобном подходе
	 * Можно порождать кучу модулей и использовать их экшены
	 *
	 * @var \SlimController\component\Module[]
	 */
	private static $modules = array();

	/** @var Slim */
	private $slim;

	/** @var \Closure  */
	private $setup_module_callback;

	/** @var \Closure[]  */
	private $mode_setup = array();

	/**
	 * @param Slim $slim
	 */
	public function __construct(Slim $slim)
	{
		// для слима
		$this->setupDefaults();
		$this->slim = $slim;

		$this->slim->container->singleton('router', function ($c) {
			return new Router();
		});

		#todo-mf: надо как-то перелопатить...
		$this->slim->error(function($error) use ($slim) {
			$slim->halt(500, $error);
		});

		$this->slim->notFound(function() use ($slim) {
			$slim->halt(404, false);
		});
	}

	/**
	 * @return Slim
	 */
	public function Slim()
	{
		return $this->slim;
	}

	/**
	 * ВНИМАНИЕ!!!!
	 *
	 *      CLOSURE ДОЛЖЕН ВОЗВРАЩАТЬ ИНСТАНС \SlimController\component\Module
	 *
	 * @param callable $callback
	 */
	public function registerModuleDispatcherCallback(\Closure $callback)
	{
		$this->setup_module_callback = $callback;
	}

	/**
	 * @param $module
	 * @return component\Module
	 * @throws \Exception
	 */
	public function createModule($module)
	{
		if (!isset(self::$modules[$module]))
		{
			if (!is_callable($this->setup_module_callback)) {
				throw new \Exception('Need to provide "controllerClassMap" callback for dispatch module.');
			}

			/** @var \SlimController\component\Module $module_object */
			$module_object = call_user_func($this->setup_module_callback, $module, $this);

			if (!is_subclass_of($module_object, 'SlimController\component\Module')) {
				throw new \Exception('Unsupported type of module');
			}

			self::$modules[$module] = $module_object;
		}

		return self::$modules[$module];
	}

	/**
	 * Создает объект маршрута для урла
	 *
	 * @param $route
	 * @param $action
	 * @return \Slim\Route
	 */
	public function add_route($route, $action)
	{
		$me = $this;

		$map_route = function() use ($me, $action)
		{
			if ($action instanceof \Closure) {
				call_user_func_array($action, func_get_args());
			}
			else
			{
				if (!strpos($action, "::")) {
					throw new \Exception('Wrong action format');
				}

				list($module_name, $action) = explode("::", $action);
				$method = $me->createModule($module_name);

				if (!$method->__check_access()) {
					throw new \Exception('Access denied');
				}

				$method->run_action($action, func_get_args());
			}
		};

		/** @var \Slim\Route $route_object */
		$route_object = $this->slim->get($route, $map_route);

		// setup default route name
		if (is_string($action)) {
			$route_object->name($action);
		}

		// default is GET && POST
		$route_object->setHttpMethods('GET', 'POST');

		return $route_object;
	}

	/**
	 * @param $command
	 * @param $action
	 */
	public function add_command($command, $action)
	{
		$this->add_route($command, $action)->setHttpMethods(self::CONSOLE_METHOD);
	}

	/**
	 * @param string $mode
	 */
	public function run($mode=Controller::MODE_WEB)
	{
		// если есть setup-callback - запускаем
		if (isset($this->mode_setup[$mode]) && is_callable($this->mode_setup[$mode])) {
			call_user_func($this->mode_setup[$mode], $this);
		}

		// if under console
		if ($mode==self::MODE_CLI)
		{
			$path = isset($_SERVER['argv'][1]) ?
				$_SERVER['argv'][1] : null;

			if ($path)
			{
				/** @var \Slim\Route[] $routes */
				$routes = $this->slim->router
					->getMatchedRoutes(self::CONSOLE_METHOD, $path);

				foreach ($routes as $route) {
					if ($route->dispatch()) {
						return;
					}
				}
			}

			print "Unknown command. Exit. \r\n";
		}
		else {
			$this->Slim()->run();
		}
	}

	/**
	 * @param callable $callback
	 * @param string $mode
	 */
	public function setup(\Closure $callback, $mode=Controller::MODE_WEB)
	{
		$this->mode_setup[$mode] = $callback;
	}

	/**
	 * Тупорылый Slim кидает кучу сраных E_NOTICE
	 * Так как нихрена не проверяет существование переменных
	 */
	private function setupDefaults()
	{
		if (!isset($_SERVER['REQUEST_METHOD'])) {
			$_SERVER['REQUEST_METHOD'] = 'CONSOLE';
		}
		if (!isset($_SERVER['REMOTE_ADDR'])) {
			$_SERVER['REMOTE_ADDR'] = '::1';
		}
		if (!isset($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = '';
		}
		if (!isset($_SERVER['SERVER_NAME'])) {
			$_SERVER['SERVER_NAME'] = '';
		}
		if (!isset($_SERVER['SERVER_PORT'])) {
			$_SERVER['SERVER_PORT'] = 0;
		}
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Controller < #
// --------------------------------------------------------------------------------------------------------------------- 