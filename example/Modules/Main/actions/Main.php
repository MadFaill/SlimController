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

	public function process()
	{
		var_dump($this->params);
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Main < #
// --------------------------------------------------------------------------------------------------------------------- 