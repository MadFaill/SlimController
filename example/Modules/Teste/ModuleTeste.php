<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 08.08.14
 * Time: 21:00
 * File: ModuleTeste.php
 * Package: Modules\Teste
 *
 */
namespace Modules\Teste;
use SlimController\component\Module;

/**
 * Class        ModuleTeste
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 08.08.14
 * @since       08.08.14
 * @version     0.01
 * @package     Modules\Teste
 */
class ModuleTeste extends Module
{
	public function test_method()
	{
		return array('a', 'test', 'data');
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END ModuleTeste < #
// --------------------------------------------------------------------------------------------------------------------- 