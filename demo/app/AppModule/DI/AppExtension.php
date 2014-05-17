<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\AppModule\DI;

use Flame\Modules\Providers\IParametersProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\IRouterProvider;
use Nette\Application\Routers\Route;
use Nette\DI\CompilerExtension;

class AppExtension extends CompilerExtension implements IRouterProvider, IParametersProvider, IPresenterMappingProvider
{

	/**
	 * Returns array of ServiceDefinition,
	 * that will be appended to setup of router service
	 *
	 * @example return array(new NetteRouteMock('<presenter>/<action>[/<id>]', 'Homepage:default'));
	 */
	public function getRoutesDefinition()
	{
		return array(
			new Route('<module>/<presenter>/<action>[/<id>]', array(
				'module' => 'App',
				'Presenter' => 'Homepage',
				'action' => 'default',
				'id' => null
			))
		);
	}

	/**
	 * Return array of parameters,
	 * which you want to add into DIC
	 *
	 * @example return array('images' => 'path/to/folder/with/images');
	 * @return array
	 */
	public function getParameters()
	{
		return array(
			//'images' => '%wwwDir%/path/to/folder/with/images',
			'consoleMode' => true,
			'appDir' => 'aa'
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
			'*' => 'App\*Module\Presenters\*Presenter'
		);
	}
}