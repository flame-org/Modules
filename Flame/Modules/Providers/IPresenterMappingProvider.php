<?php
/**
 * Class IPresenterMappingProvider
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 22.07.13
 */

namespace Flame\Modules\Providers;

use Flame\Modules\Configurators\IPresenterMappingConfig;

interface IPresenterMappingProvider
{

	/**
	 * Setup presenter mapping : ClassNameMask => PresenterNameMask
	 *
	 * @example https://gist.github.com/jsifalda/50bedd439ab23df57058
	 * @param IPresenterMappingConfig &$presenterMappingConfig
	 *
	 * @return void
	 */
	public function setupPresenterMapping(IPresenterMappingConfig &$presenterMappingConfig);
}