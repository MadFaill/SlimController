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
		print "Params: \r\n";
		var_dump($this->params);
		print "Module call: \r\n";
		var_dump($this->module->load_list());
		print "Slim call: \r\n";
		var_dump($this->module->getController()->Slim()->environment);
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Main < #
// --------------------------------------------------------------------------------------------------------------------- 