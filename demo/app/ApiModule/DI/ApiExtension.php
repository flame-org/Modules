<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\ApiModule\DI;

use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\IRouterProvider;
use Flame\Rest\Application\Routers\RestRoute;
use Nette\Application\Routers\Route;
use Nette\DI\CompilerExtension;

class ApiExtension extends CompilerExtension implements IRouterProvider, IPresenterMappingProvider
{

	/**
	 * Returns array of ServiceDefinition,
	 * that will be appended to setup of router service
	 *
	 * @example https://github.com/nette/sandbox/blob/master/app/router/RouterFactory.php - createRouter()
	 * @return \Nette\Application\IRouter
	 */
	public function getRoutesDefinition()
	{
		new Route('Flame\Rest\Application\Routers\RestRoute', array('Api'));
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