<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\HelperModule\DI;

use Flame\Modules\Configurators\ITemplateHelpersConfig;
use Flame\Modules\Providers\ITemplateHelpersProvider;
use Nette\DI\CompilerExtension;

class HelperExtension extends CompilerExtension implements ITemplateHelpersProvider
{

	/**
	 * Setup helpers definitions or providers which will add as filters into your app
	 *
	 * @example https://gist.github.com/jsifalda/7f570f94974b62163117
	 * @param ITemplateHelpersConfig &$templateHelpersConfig
	 *
	 * @return void
	 */
	public function setupHelpers(ITemplateHelpersConfig &$templateHelpersConfig)
	{
		$templateHelpersConfig->addHelperClass('App\HelperModule\Template\HelperProvider');
	}

}