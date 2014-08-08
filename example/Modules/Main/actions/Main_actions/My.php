<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 08.08.14
 * Time: 20:53
 * File: My.php
 * Package: Modules\Main\actions\Main_actions
 *
 */
namespace Modules\Main\actions\Main_actions;
use SlimController\component\Action;

/**
 * Class        My
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 08.08.14
 * @since       08.08.14
 * @version     0.01
 * @package     Modules\Main\actions\Main_actions
 */
class My extends Action
{
	public function test_me()
	{
		var_dump(get_class($this));
		var_dump(get_class($this->action));
		var_dump(get_class($this->module));
	}

	public function call_module()
	{
		$controller = $this->module->getController();

		/** @var \Modules\Teste\ModuleTeste $module */
		$module = $controller->createModule('Teste');

		return $module->test_method();
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END My < #
// --------------------------------------------------------------------------------------------------------------------- 