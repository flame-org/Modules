<?php

/**
 * @author Ondřej Záruba
 */
class TemplateHelpersProviderExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\ITemplateHelpersProvider
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('testHelper'))
			->setClass('TestHelper');
	}

	/**
	 * Return list of helpers definitions or providers
	 *
	 * @example https://gist.github.com/jsifalda/7f570f94974b62163117
	 * @param \Flame\Modules\Configurators\ITemplateHelpersConfig &$templateHelpersConfig
	 * @return void
	 */
	public function setupHelpers(\Flame\Modules\Configurators\ITemplateHelpersConfig &$templateHelpersConfig)
	{
		$templateHelpersConfig
			->addHelper('flamehelper', array(new TestHelper(), 'process'))
			->addHelper('flamehelper2', array($this->prefix('@testHelper'), 'process'))
			->addHelperClass('TestHelper2');

	}


}
