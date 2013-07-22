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
	->addConfig(__DIR__ . '/config/modules.php')
	->register();
return $configurator
```
*Look at [implementation of creating modules installator](https://github.com/flame-org/Framework/commit/a41320cc594122d6962e7a9f32c09553ae8a6ed9#L0R57)*

####Modules.php
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
**If you favor NEON, you can use modules.neon configuration.**

####That's all, nothing more! Simple!

##Bonus?!
**You can use Flame\Modules\Providers\IConfigProvider for loading additional neon configuration.**

Or implement one of these interface: IRouterProvider & IPresenterMappingProvider.

###What next?
Look at [this implementation of modules on steroids](https://bitbucket.org/enlan/).