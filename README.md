#Nette Modules on the Steroids [![Build Status](https://travis-ci.org/flame-org/Modules.png?branch=master)](https://travis-ci.org/flame-org/Modules)

**Simple registration of Nette modules & extensions.**

Base on [SOLID MODULAR CONCEPT](http://forum.nette.org/en/1193-extending-extensions-solid-modular-concept).

##Features

###Simple configuration
####config.neon
In config.neon register extension **Flame\Modules\DI\ModulesExtension**
```yml
extensions:
	- Flame\Modules\DI\ModulesExtension
```

####Add your extensions
Register extensions very simply
```yml
extensions:
	- Flame\Modules\DI\ModulesExtension # Do not forget to it!
	- App\AppModule\DI\AppExtension
	rest: Flame\Rest\DI\RestExtension
	events: Kdyby\Events\DI\EventsExtension
	# ...
```

That's all, nothing more! Simple!

##Examples
###IRouterProvider
```php
class AppExtension extends CompilerExtension implements Flame\Modules\Providers\IRouterProvider
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
			new Nette\Application\Routers\Route('<module>/<presenter>/<action>[/<id>]', array(
				'module' => 'App',
				'Presenter' => 'Homepage',
				'action' => 'default',
				'id' => null
			))
		);
	}
}
```

###IPresenterMappingProvider
```php
class AppExtension extends CompilerExtension implements Flame\Modules\Providers\IPresenterMappingProvider
{

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
```

###IParametersProvider
```php
class AppExtension extends CompilerExtension implements Flame\Modules\Providers\IParametersProvider
{

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
			'images' => '%wwwDir%/path/to/folder/with/images',
			'consoleMode' => true,
			'appDir' => 'aa'
		);
	}

}
```


###ITemplateHelpersProvider
```php
class HelperExtension extends CompilerExtension implements Flame\Modules\Providers\ITemplateHelpersProvider
{

	/**
	 * Return list of helpers definitions or providers
	 *
	 * @return array
	 */
	public function getHelpersConfiguration()
	{
		return array(
			'App\HelperModule\Template\HelperProvider'
		);
	}
}
```

###ILatteMacrosProvider
```php

class MacroExtension extends CompilerExtension implements Flame\Modules\ProvidersILatteMacrosProvider
{

	/**
	 * Get array of latte macros classes
	 *
	 * @return array
	 */
	public function getLatteMacros()
	{
		return array(
			'App\MacroModule\Template\MacroInstaller'
		);
	}
}
```

and more: **IErrorPresenterProvider, ITracyBarPanelsProvider, ITracyPanelsProvider**

###What next?
Look into the [tests/integration](https://github.com/flame-org/Modules/tree/master/tests/integration) for examples of usage.

Read more about this package on [blog](http://blog.jsifalda.name/post/detail/15/nette-moduly-a-vlastni-instalator-3) [CZE]