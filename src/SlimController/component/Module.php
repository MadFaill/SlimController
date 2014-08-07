<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 06.08.14
 * Time: 23:56
 * File: Module.php
 * Package: SlimController\component
 *
 */
namespace SlimController\component;
use SlimController\Controller;

/**
 * Class        Module
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 06.08.14
 * @since       06.08.14
 * @version     0.01
 * @package     SlimController\component
 */
abstract class Module
{
	/**
	 * @var \SlimController\Controller
	 */
	private $controller;

	/**
	 * @param Controller $controller
	 */
	public function __construct(Controller $controller)
	{
		$this->controller = $controller;
	}

	/**
	 * @return Controller
	 */
	final public function getController()
	{
		return $this->controller;
	}

	/**
	 * @param $name
	 * @param array $params
	 * @throws \Exception
	 */
	final protected  function process_action($name, array $params = array())
	{
		$namespace = get_class($this);
		$namespace = explode('\\', $namespace);
		array_pop($namespace);
		$namespace = '\\'.join('\\', $namespace);

		$class_name = sprintf('%s\actions\%s', $namespace, ucfirst($name), ucfirst($name));

		if (!class_exists($class_name)) {
			throw new \Exception('Action class not found!');
		}

		if (!is_subclass_of($class_name, '\SlimController\component\Action')) {
			throw new \Exception('Unsupported action type!');
		}

		/** @var \SlimController\component\Action $action */
		$action = new $class_name($this, $params);

		if (!$action->__check_access()) {
			throw new \Exception('Access denied');
		}

		$action->process();
	}

	public function run_action($action_name, array $params = array())
	{
		var_dump("run action", $action_name, $params);
	}

	public function __check_access() { return true; }
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Module < #
// --------------------------------------------------------------------------------------------------------------------- 