#Steroids Modules

**Nette modules on the Steroids**

Simple registration of Nette modules.

Support of [SOLID MODULAR CONCEPT](http://forum.nette.org/en/1193-extending-extensions-solid-modular-concept).

##Features

###Simple configuration
####Bootstrap
In bootstrap.php in conjunction with [Flame/Framework](https://github.com/flame-org/Framework/)
```php
$configurator = new \Flame\Configurator;
$configurator->createModulesInstaller()
	->addConfig(__DIR__ . '/config/extensions.php')
	->register();
return $configurator
```
*Look at [implementation of creating modules installator](https://github.com/flame-org/Framework/blob/master/Flame/Configurator.php#L46)*

####extensions.php
Register extensions very simply
````php

return array(
	'modules' => array(
		'REST' => 'Flame\Rest\DI\RestExtension',
		'doctrine' => 'Flame\Doctrine\DI\OrmExtension',
		'events' => 'Kdyby\Events\DI\EventsExtension',

		'Enlan\CategoryModule\DI\CategoryExtension',
	    'Enlan\DictionaryModule\DI\DictionaryExtension',
	    'Enlan\UserModule\DI\UserExtension',
		'Enlan\LevelModule\DI\LevelExtension',
		'Enlan\WordModule\DI\WordExtension',
		'Enlan\EnlanModule\DI\EnlanExtension',

		'Flame\CMS\AngularModule\DI\AngularExtension'
	)
);
````
**If you favor NEON, you can use extensions.neon configuration.**

That's all, nothing more! Simple!

##Killer feature ;-)

###You can use composer package [Nette Module Installer](https://github.com/flame-org/Nette-Module-Installer) for automatic installation of new extensions

##Bonus?!
**You can use Flame\Modules\Providers\IConfigProvider for loading additional neon configuration.**

Or implement one of these interface: IRouterProvider & IPresenterMappingProvider & ILatteMacrosProvider.

###What next?
Look at [this implementation of modules on steroids](https://bitbucket.org/enlan/).