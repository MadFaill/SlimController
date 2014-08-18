<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 08.08.14
 * Time: 20:37
 * File: Component.php
 * Package: SlimController\component\base
 *
 */

namespace SlimController\component\base;
use SlimController\component\Module;

/**
 * Class        Action
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 06.08.14
 * @since       06.08.14
 * @version     0.01
 * @package     SlimController\component\base
 */
abstract class Component
{
	/**
	 * @var \SlimController\component\Action[]
	 */
	private static $actions = array();

	/**
	 * @param $name
	 * @return \SlimController\component\Action
	 * @throws \Exception
	 */
	final public function createAction($name)
	{
		$namespace = get_class($this);

		if ($this instanceof Module)
		{
			$namespace = explode('\\', $namespace);
			array_pop($namespace);
			$namespace = '\\'.join('\\', $namespace);
			$format = '%s\actions\%s';
		}
		else {
			$format = '%s_actions\%s';
		}

		return $this->createActionWithNamespace($name, $namespace, $format);
	}

	/**
	 * @param $name
	 * @param $namespace
	 * @param string $format
	 * @return \SlimController\component\Action
	 * @throws \Exception
	 */
	final public function createActionWithNamespace($name, $namespace, $format = '%s\actions\%s')
	{
		$class_name = sprintf($format, $namespace, ucfirst($name), ucfirst($name));

		if (!isset(self::$actions[$class_name]))
		{
			if (!class_exists($class_name)) {
				throw new \Exception('Action class not found!');
			}

			if (!is_subclass_of($class_name, '\SlimController\component\Action')) {
				throw new \Exception('Unsupported action type!');
			}

			/** @var \SlimController\component\Action $action */
			self::$actions[$class_name] = new $class_name($this);
		}

		return self::$actions[$class_name];
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Component < #
// ---------------------------------------------------------------------------------------------------------------------