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
	 *
	 * @example return array('*' => 'Booking\*Module\Presenters\*Presenter');
	 * @return array
	 */
	public function getPresenterMapping();
}