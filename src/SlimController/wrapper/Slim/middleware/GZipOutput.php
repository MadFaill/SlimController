<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 08.08.14
 * Time: 20:34
 * File: GZipOutput.php
 * Package: SlimController\wrapper\Slim\middleware
 *
 */
namespace SlimController\wrapper\Slim\middleware;

use Slim\Middleware;

/**
 * Class        GZipOutput
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 08.08.14
 * @since       08.08.14
 * @version     0.01
 * @package     SlimController\wrapper\Slim\middleware
 */
class GZipOutput extends Middleware
{
	public function call()
	{
		$gzip = true;

		//If no encoding was given - then it must not be able to accept gzip pages
		$accept = $this->app->request->headers->get('HTTP_ACCEPT_ENCODING');
		if( !$accept ) {
			$gzip = false;
		}

		//If zlib is not ALREADY compressing the page - and ob_gzhandler is set
		if (( ini_get('zlib.output_compression') == 'On'
				OR ini_get('zlib.output_compression_level') > 0 )
			OR ini_get('output_handler') == 'ob_gzhandler' ) {
			$gzip = false;
		}

		// if no loaded
		if ( !extension_loaded( 'zlib' ) || (strpos($accept, 'gzip') === FALSE) ) {
			$gzip = false;
		}

		if ($gzip)
		{
			$raw = ob_get_clean();
			ob_start('ob_gzhandler');
			print $raw;
		}

		$this->next->call();
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END GZipOutput < #
// --------------------------------------------------------------------------------------------------------------------- 