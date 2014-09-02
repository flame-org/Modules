<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\MacroModule\DI;

use Flame\Modules\Providers\ILatteMacrosProvider;
use Nette\DI\CompilerExtension;

class MacroExtension extends CompilerExtension implements ILatteMacrosProvider
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