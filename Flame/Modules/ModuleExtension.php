<?php
/**
 * Class ModuleExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 17.07.13
 */
namespace Flame\Modules;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Utils\Strings;

abstract class ModuleExtension extends CompilerExtension implements IModuleExtension
{

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getShortName();
	}

	/**
	 * @param $name
	 * @return string
	 */
	public function getConfigPath($name)
	{
		return dirname($this->getReflection()->getFileName()) . '/../config/' . (string) $name . '.neon';
	}

	/**
	 * @param bool $pretty
	 * @return string
	 */
	public static function getShortName($pretty = true)
	{
		$name = self::getReflection()->getShortName();
		if($pretty === true) {
			if(Strings::contains($name, 'Extension')) {
				$name = str_replace('Extension', '', $name);
			}

			$name = Strings::lower($name);
		}

		return $name;
	}

}