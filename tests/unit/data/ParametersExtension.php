<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */

class ParametersExtension extends \Nette\DI\CompilerExtension implements \Flame\Modules\Providers\IParametersProvider
{

	/**
	 * Return array of parameters,
	 * which you want to add into DIC
	 *
	 * @example https://gist.github.com/jsifalda/59cd5a0c6f8a05e49ffa
	 * @param \Flame\Modules\Configurators\IParametersConfig &$parametersConfig
	 *
	 * @return void
	 */
	public function setupParameters(\Flame\Modules\Configurators\IParametersConfig &$parametersConfig)
	{
		$parametersConfig->setParameter('test', 'param value');
	}


}