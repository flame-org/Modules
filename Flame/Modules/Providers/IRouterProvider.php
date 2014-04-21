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
	 * @example return array(new NetteRouteMock('<presenter>/<action>[/<id>]', 'Homepage:default'));
	 */
	public function getRoutesDefinition();
}