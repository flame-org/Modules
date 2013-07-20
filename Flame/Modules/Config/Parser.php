<?php
/**
 * Class Parser
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 18.07.13
 */
namespace Flame\Modules\Config;

use Flame\Modules\Providers\IConfigProvider;
use Flame\Modules\Providers\IRouterProvider;
use Nette\Object;
use Nette\Configurator;

class Parser extends Object
{

	/** @var  \Nette\Configurator */
	private $configurator;

	/** @var array  */
	private $routesDefinition = array();

	/**
	 * @param Configurator $configurator
	 */
	function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * @param IConfigProvider $extension
	 * @return $this
	 */
	public function parseConfigProvider(IConfigProvider $extension)
	{
		$files = $extension->getConfigFiles();
		if(count($files)) {
			foreach ($files as $file) {
				$this->configurator->addConfig($file);
			}
		}

		return $this;
	}

	/**
	 * @param IRouterProvider $extension
	 */
	public function parseRouterProvider(IRouterProvider $extension)
	{
		$this->routesDefinition = array_merge($this->routesDefinition, $extension->getRoutesDefinition());
	}

	/**
	 * @return array
	 */
	public function getDefinedRoutes()
	{
		return $this->routesDefinition;
	}
}