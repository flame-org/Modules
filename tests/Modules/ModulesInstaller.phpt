<?php
/**
 * Test: Flame\Tests\Modules\ModulesInstaller.
 *
 * @testCase Flame\Tests\Modules\ModulesInstallerTest
 * @package Flame\Tests\Modules
 */
 
namespace Flame\Tests\Modules;

use Flame\Modules\DI\ModulesExtension;
use Flame\Modules\ModulesInstaller;
use Flame\Tester\MockTestCase;
use Nette;
use Tester\Assert;
use Flame\Modules\DI\ConfiguratorHelper;

require_once __DIR__ . '/../bootstrap.php';

class ModulesInstallerTest extends MockTestCase
{

	/** @var  ModulesInstaller */
	private $installer;

	/** @var  \Mockista\MockInterface */
	private $configuratorHelperMock;

    public function setUp()
    {
        parent::setUp();

	    $this->configuratorHelperMock = $this->mockista->create(ConfiguratorHelper::getReflection()->getName());
	    $this->installer = new ModulesInstaller($this->configuratorHelperMock);
    }
    
    public function testRegister()
    {
	    $this->configuratorHelperMock->expects('registerExtension')
		    ->with(new ModulesExtension, ModulesExtension::getShortName())
		    ->once();
	    Assert::type(ModulesInstaller::getReflection()->getName(), $this->installer->register());
    }
}
run(new ModulesInstallerTest);