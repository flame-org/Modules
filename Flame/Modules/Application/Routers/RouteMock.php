<?php
/**
 * Class Mock
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 08.09.13
 */
namespace Flame\Modules\Application\Routers;

use Nette\Application\IRouter;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Reflection\ClassType;

class RouteMock extends Object implements IRouteMock
{

	/** @var string  */
	public $class;

	/** @var array  */
	public $args;

	/**
	 * @param string $class
	 * @param array $args
	 */
	function __construct($class, array $args = array())
	{
		$this->args = $args;
		$this->class = (string) $class;
	}

	/**
	 * @return IRouter
	 * @throws \Nette\InvalidStateException
	 */
	public function getInstance()
	{
		$route = new ClassType($this->class);
		$route = $route->newInstanceArgs($this->args);

		if($route instanceof IRouter) {
			return $route;
		}

		throw new InvalidStateException('Class "' . $this->class . '" is not instance of Nette\Application\IRouter');
	}

}