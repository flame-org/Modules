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
	- App\AppModule\DI\AppExtension
	rest: Flame\Rest\DI\RestExtension
	events: Kdyby\Events\DI\EventsExtension
	# ...
	- Flame\Modules\DI\ModulesExtension # Do not forget to add it!
```

That's all, nothing more! Simple!

**TIP!** *Make sure the ModulesExtension is registered as the last nette extensions. You will avoid a lot of misunderstanding.*

##Examples
###[IRouterProvider](https://github.com/flame-org/Modules/blob/master/Flame/Modules/Providers/IRouterProvider.php)
```php
class AppExtension extends CompilerExtension implements Flame\Modules\Providers\IRouterProvider
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
		return new Nette\Application\Routers\Route('<module>/<presenter>/<action>[/<id>]', array(
			'module' => 'App',
			'Presenter' => 'Homepage',
			'action' => 'default',
			'id' => null
		);
	}
}
```

###NEW!
**You can add your separated service as your router factory**
```php
class AppExtension extends CompilerExtension
{
	public function loadConfiguration()
    	{
    		$builder = $this->getContainerBuilder();
    		$builder->addDefinition('service.routerFactory')
    			->setClass('Modules\RouterFactory') // YOUR ROUTER FACTORY CLASS
    			->addTag(Flame\Modules\ModulesExtension::TAG_ROUTER); // DONT FORGET TO ADD THE TAG!
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
Look into the [project homepage](http://flame-org.github.io/Modules/) for more details.

Read more about this package on [blog](http://blog.jsifalda.name/post/detail/15/nette-moduly-a-vlastni-instalator-3) [CZE].

Based on [SOLID MODULAR CONCEPT](http://forum.nette.org/en/1193-extending-extensions-solid-modular-concept).
