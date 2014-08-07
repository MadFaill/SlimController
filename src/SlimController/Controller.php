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
	/** @var Slim */
	private $slim;

	/** @var \Closure  */
	private $setup_module_callback;

	const CONSOLE_METHOD = 'CONSOLE';

	/**
	 * @param Slim $slim
	 */
	public function __construct(Slim $slim)
	{
		$this->slim = $slim;
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
		$this->setup_module_callback = $callback->bindTo($this);
	}

	/**
	 * @param $module
	 * @return component\Module
	 * @throws \Exception
	 */
	public function createModule($module)
	{
		if (!is_callable($this->setup_module_callback)) {
			throw new \Exception('Need to provide "controllerClassMap" callback for dispatch module.');
		}

		/** @var \SlimController\component\Module $module */
		$module = call_user_func($this->setup_module_callback, $module);

		if (!is_subclass_of($module, 'SlimController\component\Module')) {
			throw new \Exception('Unsupported type of module');
		}

		return $module;
	}

	/**
	 * Создает объект маршрута для урла
	 *
	 * @param $route
	 * @param $action
	 * @return \Slim\Route
	 */
	public function mapRoute($route, $action)
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
	public function mapCommand($command, $action)
	{
		$this->mapRoute($command, $action)->setHttpMethods(self::CONSOLE_METHOD);
	}

	public function run()
	{
		if (isset($_SERVER['argv']))
		{
			$path = $_SERVER['argv'][1];
			$routes = $this->slim->router->getMatchedRoutes(self::CONSOLE_METHOD, $path);

			/** @var \Slim\Route $route */
			foreach ($routes as $route) {
				if ($route->dispatch()) {
					return true;
				}
			}
		}

		$this->slim->run();
		return false;
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Controller < #
// --------------------------------------------------------------------------------------------------------------------- 