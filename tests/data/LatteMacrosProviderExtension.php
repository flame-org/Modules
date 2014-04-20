<?php

/**
 * @author Ondřej Záruba
 */
class LatteMacrosProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ILatteMacrosProvider
{
	/**
	 * Get array of latte macros classes
	 *
	 * @return array
	 */
	public function getLatteMacros()
	{
		return array(
			'TestMacro'
		);
	}
}