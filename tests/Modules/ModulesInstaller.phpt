<?php
/**
 * Test: Flame\Tests\Modules\ModulesInstaller.
 *
 * @testCase Flame\Tests\Modules\ModulesInstallerTest
 * @package Flame\Tests\Modules
 */
 
namespace Flame\Tests\Modules;

use Flame\Tester\MockTestCase;
use Nette;
use Flame\Modules\Config\ConfigFile;
use Nette\Configurator;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ModulesInstallerTest extends MockTestCase
{
	public function testTrue()
	{
		Assert::true(true);
	}
}

run(new ModulesInstallerTest);