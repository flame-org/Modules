<?php
/**
 * Class IRouteMock
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 08.09.13
 */

namespace Flame\Modules\Application\Routers;

interface IRouteMock
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function getRouter();
}