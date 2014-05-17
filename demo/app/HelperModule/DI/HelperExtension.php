<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\HelperModule\DI;

use Flame\Modules\Providers\ITemplateHelpersProvider;
use Nette\DI\CompilerExtension;

class HelperExtension extends CompilerExtension implements ITemplateHelpersProvider
{

	/**
	 * Return list of helpers definitions or providers
	 *
	 * @return array
	 */
	public function getHelpersConfiguration()
	{
		return array(
			'App\HelperModule\Template\HelperProvider'
		);
	}
}