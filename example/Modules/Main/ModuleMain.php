<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 07.08.14
 * Time: 21:10
 * File: ModuleMain.php
 * Package: Modules\Main
 *
 */
namespace Modules\Main;
use SlimController\component\Module;

/**
 * Class        ModuleMain
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 07.08.14
 * @since       07.08.14
 * @version     0.01
 * @package     Modules\Main
 */
class ModuleMain extends Module
{
	/**
	 * @param $name
	 * @param array $options
	 */
	public function run_action($name, array $options = array())
	{
		$this->process_action($name, $options);
	}

	public function load_list()
	{
		return array(1,2,3,4,5,6);
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END ModuleMain < #
// --------------------------------------------------------------------------------------------------------------------- 