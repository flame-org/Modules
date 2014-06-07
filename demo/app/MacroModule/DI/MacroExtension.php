<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\MacroModule\DI;

use Flame\Modules\Providers\ILatteMacrosProvider;
use Nette\DI\CompilerExtension;

class MacroExtension extends CompilerExtension implements ILatteMacrosProvider
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('macro2Installer'))
			->setClass('App\MacroModule\Template\Macro2Installer')
			->addTag('modules.macro');
	}

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