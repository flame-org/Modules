<?php
/**
 * Test: Flame\Tests\Modules\ModulesInstaller.
 *
 * @testCase Flame\Tests\Modules\ModulesInstallerTest
 * @package Flame\Tests\Modules
 */
 
namespace Flame\Tests\Modules;

use Flame\Modules\DI\ModulesExtension;
use Flame\Modules\Extension\IDomainExtension;
use Flame\Modules\Extension\NamedExtension;
use Flame\Modules\ModulesInstaller;
use Flame\Tester\MockTestCase;
use Nette;
use Nette\Configurator;
use Tester\Assert;
use Flame\Modules\DI\ConfiguratorHelper;

require_once __DIR__ . '/../bootstrap.php';

class ExtensionWithoutCompilerAncestor extends Nette\Object
{

}

class FakeExtension extends Nette\DI\CompilerExtension
{

}

class FakeDomainExtension extends NamedExtension implements IDomainExtension
{
	/**
	 * @param Configurator $configurator
	 * @return void
	 */
	public function register(Configurator $configurator)
	{
		return;
	}
}

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

	public function testRegisterExtension()
	{
		$this->configuratorHelperMock->expects('registerExtension')
			->with(new ModulesExtension, ModulesExtension::getShortName())
			->once();
		Assert::type(ModulesInstaller::getReflection()->getName(), $this->installer->registerExtension('Flame\Modules\DI\ModulesExtension'));
	}

	public function testRegisterExtensionWithName()
	{
		$this->configuratorHelperMock->expects('registerExtension')
			->with(new ModulesExtension, 'name')
			->once();
		Assert::type(ModulesInstaller::getReflection()->getName(), $this->installer->registerExtension('Flame\Modules\DI\ModulesExtension', 'name'));
	}

	public function testRegisterExtensionWithoutCompilerAncestor()
	{
		Assert::throws(function () {
			$this->installer->registerExtension(new ExtensionWithoutCompilerAncestor);
		}, '\Nette\InvalidArgumentException');
	}

	public function testRegisterExtensionWithoutDefinedName()
	{
		Assert::throws(function () {
			$this->installer->registerExtension(new FakeExtension());
		}, '\Nette\InvalidStateException');
	}

	public function testRegisterExtensionDomainInterface()
	{
		$this->configuratorHelperMock->expects('getConfigurator')
			->once()
			->andReturn(new Configurator);
		Assert::type(ModulesInstaller::getReflection()->getName(), $this->installer->registerExtension(new FakeDomainExtension));
	}
}
run(new ModulesInstallerTest);