<?php
/**
 * Class IPresenterMappingProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 22.07.13
 */

namespace Flame\Modules\Providers;


interface IPresenterMappingProvider
{

	/**
	 * Returns array of ClassNameMask => PresenterNameMask
	 * @see https://github.com/nette/nette/blob/master/Nette/Application/PresenterFactory.php#L138
	 * @return array
	 */
	public function getPresenterMapping();
}