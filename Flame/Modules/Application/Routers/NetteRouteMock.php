<?php
/**
 * Class RouteMock
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 08.09.13
 */
namespace Flame\Modules\Application\Routers;

use Nette\Application\Routers\Route;
use Nette;

class NetteRouteMock extends RouteMock
{

	/**
	 * @param  string  URL mask, e.g. '<presenter>/<action>/<id \d{1,3}>'
	 * @param  array|string   default values or metadata
	 * @param  int     flags
	 */
	public function __construct($mask, $metadata = array(), $flags = 0)
	{
		parent::__construct(Route::getReflection()->getName(), array($mask, $metadata, $flags));
	}

}