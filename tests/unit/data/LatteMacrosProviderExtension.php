<?php

/**
 * @author Ondřej Záruba
 */
class LatteMacrosProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ILatteMacrosProvider
{


	/**
	 * Get array with names of latte macros classes
	 *
	 * @example https://gist.github.com/jsifalda/8e781e6fc3a04038f44a
	 *
	 * @param \Flame\Modules\Configurators\ILatteMacrosConfig &$macrosConfig
	 * @return void
	 */
	public function setupMacros(\Flame\Modules\Configurators\ILatteMacrosConfig &$macrosConfig)
	{
		$macrosConfig->addMacro('TestMacro');
	}


}