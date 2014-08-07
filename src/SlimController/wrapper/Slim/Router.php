<?php
/**
 * Project: SlimController
 * User: MadFaill
 * Date: 07.08.14
 * Time: 23:36
 * File: Router.php
 * Package: SlimController\wrapper\Slim
 *
 */
namespace SlimController\wrapper\Slim;
use Slim\Router as SlimRouter;

/**
 * Class        Router
 * @description None.
 *
 * @author      MadFaill
 * @copyright   MadFaill 07.08.14
 * @since       07.08.14
 * @version     0.01
 * @package     SlimController\wrapper\Slim
 */
class Router extends SlimRouter
{
	/**
	 * @param string $name
	 * @param array $params
	 * @return mixed|string
	 * @throws \RuntimeException
	 */
	public function urlFor($name, $params=array())
	{
		$route_pattern = $this->getNamedRoute($name)->getPattern();

		if (!$this->hasNamedRoute($name)) {
			throw new \RuntimeException('Named route not found for name: ' . $name);
		}

		$search = array();
		foreach ($params as $key => $value) {
			$search[] = '#:' . preg_quote($key, '#') . '\+?(?!\w)#';
		}
		$pattern = preg_replace($search, $params, $route_pattern);

		$uri = preg_replace('#\(([^:]+)?:.+\)|\(|\)|\\\\#', '', $pattern);

		// стандартый функционал не позволяет достраивать в
		// named маршруты ?<query_string> соответственно - фикс
		if ($params)
		{
			// перебор, вычленение и удаление тех параметров которые есть в роуте
			foreach ($params as $param => $_) {
				if (preg_match('~\b('.$param.')\b~', $route_pattern)) {
					unset($params[$param]);
				}
			}

			// теперь уже доставляем после знака вопроса
			// если параметры остались
			if ($params) {
				$uri  = $uri.'?'.http_build_query($params);
			}
		}

		return $uri;
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// > END Router < #
// --------------------------------------------------------------------------------------------------------------------- 