<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 07.08.14
 * Time: 21:11
 * File: Main.php
 * Package: Modules\Main\Actions
 *
 */
namespace Modules\Main\actions;
use SlimController\component\Action;

/**
 * Class        Main
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 07.08.14
 * @since       07.08.14
 * @version     0.01
 * @package     Modules\Main\Actions
 */
final class Main extends Action
{
	/** @var \Modules\Main\ModuleMain */
	protected $module;

	/**
	 * Собственно, сам экшен
	 */
	public function process()
	{
		/** @var \Modules\Main\actions\Main_actions\My $action */
		$action = $this->createAction('My');

		print "Params: \r\n";
		var_dump($this->params);

		print "Module call: \r\n";
		var_dump($this->module->load_list());

		print "Sub-action: \r\n";
		$action->test_me();

		print "Teste data: \r\n";
		var_dump($action->call_module());
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Main < #
// --------------------------------------------------------------------------------------------------------------------- 