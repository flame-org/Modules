<?php

if (!class_exists('Nette\DI\CompilerExtension')) {
	if(class_exists('Nette\Config\CompilerExtension')) {
		class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
	}

	if(class_exists('Nette\Config\Compiler')) {
		class_alias('Nette\Config\Compiler', 'Nette\DI\Compiler');
	}

	if(class_exists('Nette\Config\Helpers')) {
		class_alias('Nette\Config\Helpers', 'Nette\DI\Config\Helpers');
	}
}

if(!class_exists('Nette\PhpGenerator\ClassType') && class_exists('Nette\Utils\PhpGenerator\ClassType')) {
	class_alias('Nette\Utils\PhpGenerator\ClassType', 'Nette\PhpGenerator\ClassType');
}