<?php
/**
 * Class IDomainExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 27.07.13
 */
namespace Flame\Modules\Extension;

use Nette\Config\Configurator;

interface IDomainExtension
{

	/**
	 * @param Configurator $configurator
	 * @return void
	 */
	public function register(Configurator $configurator);
} 