<?php

/**
 * @author Ondřej Záruba
 */
class PresenterMappingExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IPresenterMappingProvider
{


	/**
	 * Returns array of ClassNameMask => PresenterNameMask
	 *
	 * @example https://gist.github.com/jsifalda/50bedd439ab23df57058
	 * @param \Flame\Modules\Configurators\IPresenterMappingConfig &$presenterMappingConfig
	 *
	 * @return void
	 */
	public function setupPresenterMapping(\Flame\Modules\Configurators\IPresenterMappingConfig &$presenterMappingConfig)
	{
		$presenterMappingConfig->setMapping('FlameModule', 'Flame\Test\*Module\Presenters\*Presenter');
	}

}