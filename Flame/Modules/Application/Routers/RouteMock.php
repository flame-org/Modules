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

	/** @var  IRouter */
	private $router;

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
	public function getRouter()
	{
		if($this->router === null) {
			$this->router = $this->createInstance();
		}

		if($this->router instanceof IRouter) {
			return $this->router;
		}

		throw new InvalidStateException('Class "' . $this->class . '" is not instance of Nette\Application\IRouter');
	}

	/**
	 * @return object
	 * @throws \Nette\InvalidStateException
	 */
	protected function createInstance()
	{
		if(!class_exists($this->class)) {
			throw new InvalidStateException('Route class "' . $this->class . '" not found!');
		}

		$route = new ClassType($this->class);
		return $route->newInstanceArgs($this->args);
	}

}