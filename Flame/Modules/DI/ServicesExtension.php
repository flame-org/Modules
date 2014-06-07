<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\MissingServiceException;

class ServicesExtension extends CompilerExtension
{

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		try {
			$latte = $builder->getDefinition('nette.latteFactory');
		} catch (MissingServiceException $e) {
			$latte = $builder->getDefinition('nette.latte');
		}

		$services = $builder->findByTag('modules.macro');
		foreach($services as $serviceName => $value) {
			$service = $builder->getDefinition($serviceName);
			if ($service->getClass()) {
				$latte->addSetup('?->onCompile[] = function($engine) { ' . $service->getClass() . '::install($engine->getCompiler()); }', array('@self'));
			}
		}

	}

} 