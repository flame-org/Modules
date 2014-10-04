<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Flame\Modules\Providers;

use Flame\Modules\Configurators\IParametersConfig;

interface IParametersProvider
{

	/**
	 * Add parameters (possible rewrite) in your app DIC
	 *
	 * @example https://gist.github.com/jsifalda/59cd5a0c6f8a05e49ffa
	 * @param \Flame\Modules\Configurators\IParametersConfig &$parametersConfig
	 *
	 * @return void
	 */
	public function setupParameters(IParametersConfig &$parametersConfig);
} 