#Steroids Modules [![Build Status](https://travis-ci.org/flame-org/Modules.png?branch=master)](https://travis-ci.org/flame-org/Modules)

**Nette modules on the Steroids**

Simple registration of Nette modules & extensions.

Support of [SOLID MODULAR CONCEPT](http://forum.nette.org/en/1193-extending-extensions-solid-modular-concept).

Read more about this package on [blog](http://blog.jsifalda.name/post/detail/15/nette-moduly-a-vlastni-instalator-3) [CZE]

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
	- Flame\Modules\DI\ModulesExtension
	- Booking\AppModule\DI\AppExtension
	rest: Flame\Rest\DI\RestExtension
	doctrine: Flame\Doctrine\DI\OrmExtension
	events: Kdyby\Events\DI\EventsExtension
```

That's all, nothing more! Simple!

##Bonus?!
**You can use Flame\Modules\Providers\IConfigProvider for loading additional neon configuration.**

Or implement one of these interface: IRouterProvider & IPresenterMappingProvider & ILatteMacrosProvider [and more...](https://github.com/flame-org/Modules/tree/master/Flame/Modules/Providers)

###What next?
Look at [this implementation of modules on steroids](https://bitbucket.org/enlan/).