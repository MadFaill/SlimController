<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 07.08.14
 * Time: 23:31
 * File: Slim.php
 * Package: SlimController\wrapper
 *
 */
namespace SlimController\wrapper;
use Slim\Slim as SlimBase;
use SlimController\wrapper\Slim\Router;

/**
 * Class        Slim
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 07.08.14
 * @since       07.08.14
 * @version     0.01
 * @package     SlimController\wrapper
 */
class Slim extends SlimBase
{
	/** @var \Closure */
	private $halt_callback;

	/**
	 * @param array $user_settings
	 */
	public function __construct($user_settings=array())
	{
		$slim = $this;

		parent::__construct($user_settings);

		$this->container->singleton('router', function ($c) {
			return new Router();
		});

		$this->error(function($error) use ($slim) {
			$slim->halt(500, $error);
		});

		$this->notFound(function() use ($slim) {
			$slim->halt(404, false);
		});
	}

	/**
	 * @param callable $halt
	 */
	public function setupHalt(\Closure $halt)
	{
		$this->halt_callback = $halt->bindTo($this);
	}

	/**
	 * @param int $status
	 * @param string $message
	 */
	public function halt($status, $message = '')
	{
		$this->cleanBuffer();
		$this->response->headers->set('Content-Type', 'text/html; charset=utf8');
		$this->response->status($status);

		if (is_callable($this->halt_callback)) {
			call_user_func($this->halt_callback, $status, $message);
		}
		else
		{
			$html = '
				<html>
					<head>
						<title>%s</title>
					</head>
					<body>
						<h1>%s</h1>
					</body>
				</html>
			';
			$body = sprintf($html, 'Error '.$status, $message);
			$this->response->body($body);
		}

		$this->stop();
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Slim < #
// --------------------------------------------------------------------------------------------------------------------- 