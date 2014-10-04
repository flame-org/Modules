<?php
/**
 * Class IRouterProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules\Providers;

interface IRouterProvider
{

	/**
	 * Returns array of ServiceDefinition,
	 * that will be appended to setup of router service
	 *
	 * @example https://gist.github.com/jsifalda/4bf8cf8d28a6e52995f6
	 * @return \Nette\Application\IRouter
	 */
	public function getRoutesDefinition();

}
