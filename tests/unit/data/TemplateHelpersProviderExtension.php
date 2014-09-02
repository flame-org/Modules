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
	 * @return array
	 */
	public function getHelpersConfiguration()
	{
		return array(
			'flamehelper' => array(new TestHelper(), 'process'),
			'flamehelper2' => array($this->prefix('@testHelper'), 'process'),
			'TestHelper2'
		);
	}
}
