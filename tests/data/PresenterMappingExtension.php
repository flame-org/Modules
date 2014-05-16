<?php

/**
 * @author Ondřej Záruba
 */
class PresenterMappingExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IPresenterMappingProvider
{

	/**
	 * Returns array of ClassNameMask => PresenterNameMask
	 * @see https://github.com/nette/nette/blob/master/Nette/Application/PresenterFactory.php#L138
	 * @return array
	 */
	public function getPresenterMapping()
	{
		return array(
			'FlameModule' => 'Flame\Test\*Module\Presenters\*Presenter'
		);
	}
}