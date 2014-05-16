<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\ApiModule\DI;

use Flame\Modules\Application\Routers\NetteRouteMock;
use Flame\Modules\Application\Routers\RouteMock;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Rest\Application\Routers\RestRoute;
use Nette\DI\CompilerExtension;

class ApiExtension extends CompilerExtension implements IRouterProvider, IPresenterMappingProvider
{

	/**
	 * Returns array of ServiceDefinition,
	 * that will be appended to setup of router service
	 *
	 * @example return array(new NetteRouteMock('<presenter>/<action>[/<id>]', 'Homepage:default'));
	 * @return array
	 */
	public function getRoutesDefinition()
	{
		return array(
			new RouteMock('Flame\Rest\Application\Routers\RestRoute', array('Api'))
//			array('Flame\Rest\Application\Routers\RestRoute' => array('Api'))
//			new RestRoute('Api')
		);
	}

	/**
	 * Returns array of ClassNameMask => PresenterNameMask
	 *
	 * @example return array('*' => 'Booking\*Module\Presenters\*Presenter');
	 * @return array
	 */
	public function getPresenterMapping()
	{
		return array(
			'Api' => 'App\ApiModule\*Module\Presenters\*Presenter'
		);
	}
}