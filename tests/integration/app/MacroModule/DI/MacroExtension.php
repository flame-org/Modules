<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\MacroModule\DI;

use Flame\Modules\Configurators\ILatteMacrosConfig;
use Flame\Modules\Providers\ILatteMacrosProvider;
use Nette\DI\CompilerExtension;

class MacroExtension extends CompilerExtension implements ILatteMacrosProvider
{

	/**
	 * Setup names of latte macros classes
	 *
	 * @example https://gist.github.com/jsifalda/8e781e6fc3a04038f44a
	 *
	 * @param ILatteMacrosConfig &$macrosConfig
	 * @return void
	 */
	public function setupMacros(ILatteMacrosConfig &$macrosConfig)
	{
		$macrosConfig->addMacro('App\MacroModule\Template\MacroInstaller');
	}


}