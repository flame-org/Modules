<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\MacroModule\Template;

use Latte\Macros\MacroSet;
use Latte\Compiler;

class Macro2Installer
{
	const OUTPUT = 'Macro2';

	/**
	 * @param Compiler $compiler
	 * @return void|static
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('macro2', "echo '" . self::OUTPUT . "'");
	}

} 