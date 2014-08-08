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
use SlimController\component\base\Component;

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
abstract class Action extends Component
{
	/** @var Module */
	protected $module;

	/** @var  Action */
	protected $action;

	/** @var array параметры из запроса */
	protected $params = array();

	/**
	 * @param Component $parent
	 */
	final public function __construct(Component $parent)
	{
		if ($parent instanceof Module) {
			$this->module = $parent;
		}

		if ($parent instanceof Action) {
			$this->module = $parent->module;
			$this->action = $parent;
		}
	}

	final public function setParams(array $params)
	{
		$this->params = $params;
	}

	public function process() {}

	public function __check_access() { return true; }
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Action < #
// --------------------------------------------------------------------------------------------------------------------- 