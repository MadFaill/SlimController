<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 06.08.14
 * Time: 23:56
 * File: Action.php
 * Package: SlimController\component
 *
 */
namespace SlimController\component;

/**
 * Class        Action
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 06.08.14
 * @since       06.08.14
 * @version     0.01
 * @package     SlimController\component
 */
abstract class Action
{
	/** @var Module */
	protected $module;

	/** @var array параметры из запроса */
	protected $params = array();

	/**
	 * @param Module $module
	 * @param array $params
	 */
	final public function __construct(Module $module, array $params = array())
	{
		$this->module = $module;
		$this->params = $params;
	}

	public function process() {}

	public function __check_access() { return true; }
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Action < #
// --------------------------------------------------------------------------------------------------------------------- 