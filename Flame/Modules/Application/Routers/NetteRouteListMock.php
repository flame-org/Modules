<?php
/**
 * Class NetteRouteListMock
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 08.09.13
 */
namespace Flame\Modules\Application\Routers;

use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\OutOfRangeException;

class NetteRouteListMock extends Object implements \ArrayAccess, \Countable, \IteratorAggregate, IRouteMock
{

	/** @var array  */
	public $list = array();

	/** @var \Flame\Modules\Application\Routers\RouteMock  */
	public $factory;

	/**
	 * @param null $module
	 */
	function __construct($module = null)
	{
		$this->factory = new RouteMock('Nette\Application\Routers\RouteList', array($module));
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function getRouter()
	{
		/** @var \Nette\Application\Routers\RouteList $routeList */
		$routeList = $this->factory->getRouter();

		foreach($this->list as $route) {
			if($route instanceof IRouteMock) {
				$route = $route->getRouter();
			}

			$routeList[] = $route;
		}

		return $routeList;
	}

	/**
	 * Returns an iterator over all items.
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->list);
	}


	/**
	 * Returns items count.
	 * @return int
	 */
	public function count()
	{
		return count($this->list);
	}


	/**
	 * @param mixed $index
	 * @param mixed $value
	 * @throws \Nette\OutOfRangeException
	 * @throws \Nette\InvalidArgumentException
	 */
	public function offsetSet($index, $value)
	{
		if (!$value instanceof IRouteMock) {
			throw new InvalidArgumentException("Argument must be IRouteMock descendant.");
		}

		if ($index === NULL) {
			$this->list[] = $value;

		} elseif ($index < 0 || $index >= count($this->list)) {
			throw new OutOfRangeException("Offset invalid or out of range");

		} else {
			$this->list[(int) $index] = $value;
		}
	}


	/**
	 * @param mixed $index
	 * @return mixed
	 * @throws \Nette\OutOfRangeException
	 */
	public function offsetGet($index)
	{
		if ($index < 0 || $index >= count($this->list)) {
			throw new OutOfRangeException("Offset invalid or out of range");
		}
		return $this->list[(int) $index];
	}


	/**
	 * @param mixed $index
	 * @return bool
	 */
	public function offsetExists($index)
	{
		return $index >= 0 && $index < count($this->list);
	}


	/**
	 * @param mixed $index
	 * @throws \Nette\OutOfRangeException
	 */
	public function offsetUnset($index)
	{
		if ($index < 0 || $index >= count($this->list)) {
			throw new OutOfRangeException("Offset invalid or out of range");
		}
		array_splice($this->list, (int) $index, 1);
	}
}