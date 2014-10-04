<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\AppModule\DI;

use Flame\Modules\Configurators\IParametersConfig;
use Flame\Modules\Configurators\IPresenterMappingConfig;
use Flame\Modules\Providers\IParametersProvider;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Flame\Modules\Providers\IRouterProvider;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\DI\CompilerExtension;

class AppExtension extends CompilerExtension implements IRouterProvider, IParametersProvider, IPresenterMappingProvider
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
		$routeList = new RouteList;

		$routeList[] = new Route('<module>/<presenter>/<action>[/<id>]', array(
			'module' => 'App',
			'Presenter' => 'Home',
			'action' => 'default',
			'id' => null
		));

		$routeList[] = new Route('/', 'App:Home:default', Route::ONE_WAY);

		return $routeList;
	}

	/**
	 * Add parameters (possible rewrite) in your app DIC
	 *
	 * @example https://gist.github.com/jsifalda/59cd5a0c6f8a05e49ffa
	 * @param \Flame\Modules\Configurators\IParametersConfig &$parametersConfig
	 *
	 * @return void
	 */
	public function setupParameters(IParametersConfig &$parametersConfig)
	{
		$parametersConfig
			->setParameter('consoleMode', true)
//			->setParameter('images', '%wwwDir%/path/to/folder/with/images')
			->setParameter('appDir', 'aa');
	}

	/**
	 * Setup presenter mapping : ClassNameMask => PresenterNameMask
	 *
	 * @example https://gist.github.com/jsifalda/50bedd439ab23df57058
	 * @param IPresenterMappingConfig &$presenterMappingConfig
	 *
	 * @return void
	 */
	public function setupPresenterMapping(IPresenterMappingConfig &$presenterMappingConfig)
	{
		$presenterMappingConfig->setMapping('*', 'App\*Module\Presenters\*Presenter');
	}
}
