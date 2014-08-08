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
use SlimController\component\base\Component;

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
abstract class Module extends Component
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
		$action = $this->createAction($name);
		$action->setParams($params);

		if (!$action->__check_access()) {
			throw new \Exception('Access denied');
		}

		$action->process();
	}

	/**
	 * @param $action_name
	 * @param array $params
	 */
	public function run_action($action_name, array $params = array())
	{
		$this->process_action($action_name, $params);
	}

	public function __check_access() { return true; }
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Module < #
// --------------------------------------------------------------------------------------------------------------------- 