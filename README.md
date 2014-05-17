#Nette Modules on the Steroids [![Build Status](https://travis-ci.org/flame-org/Modules.png?branch=master)](https://travis-ci.org/flame-org/Modules)

**Simple registration and management of Nette modules & extensions.**

Read more on [project homepage](http://flame-org.github.io/Modules/).

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
###[IRouterProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/IRouterProvider.php)
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

###[IPresenterMappingProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/IPresenterMappingProvider.php)
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

###[IParametersProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/IParametersProvider.php)
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


###[ITemplateHelpersProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/ITemplateHelpersProvider.php)
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

###[ILatteMacrosProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/ILatteMacrosProvider.php)
```php

class MacroExtension extends CompilerExtension implements Flame\Modules\Providers\ILatteMacrosProvider
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

###[IErrorPresenterProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/IErrorPresenterProvider.php)
```php
class ErrorExtension extends CompilerExtension implements Flame\Modules\Providers\IErrorPresenterProvider
{

	/**
	 * Return name of error presenter
	 *
	 * @return string
	 */
	public function getErrorPresenterName()
	{
		return 'Error:CustomError';
	}
}
```

and more: **[ITracyBarPanelsProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/ITracyPanelsProvider.php),
[ITracyPanelsProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/ITracyBarPanelsProvider.php)**

###What next?
Look into the [demo folder](https://github.com/flame-org/Modules/tree/master/demo) for working demo application.

Read more about this package on [blog](http://blog.jsifalda.name/post/detail/15/nette-moduly-a-vlastni-instalator-3) [CZE].

Based on [SOLID MODULAR CONCEPT](http://forum.nette.org/en/1193-extending-extensions-solid-modular-concept).