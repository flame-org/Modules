<?php
/**
 * Class RouteMock
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 08.09.13
 */
namespace Flame\Modules\Application\Routers;

use Nette\Object;

class NetteRouteMock extends Object implements IRouteMock
{

	/** @var \Flame\Modules\Application\Routers\RouteMock  */
	public $factory;

	/**
	 * @param  string  URL mask, e.g. '<presenter>/<action>/<id \d{1,3}>'
	 * @param  array|string   default values or metadata
	 * @param  int     flags
	 */
	public function __construct($mask, $metadata = array(), $flags = 0)
	{
		$this->factory = new RouteMock('Nette\Application\Routers\Route', array($mask, $metadata, $flags));
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function getRouter()
	{
		return $this->factory->getRouter();
	}
}