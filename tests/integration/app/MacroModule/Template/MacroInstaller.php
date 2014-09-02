<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace App\MacroModule\Template;

use Latte\Macros\MacroSet;
use Latte\Compiler;

class MacroInstaller extends MacroSet
{

	const OUTPUT = 'Macro1';

	/**
	 * @param Compiler $compiler
	 * @return void|static
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('macro1', "echo '" . self::OUTPUT . "'");
	}
} 