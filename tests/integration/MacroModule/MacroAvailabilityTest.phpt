<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Test\MacroModule;

use App\MacroModule\Presenters\MacroPresenter;
use App\MacroModule\Template\MacroInstaller;
use Tester\Assert;
use Tester\TestCase;
use Nette;

require __DIR__ . '/../bootstrap.php';

class MacroAvailabilityTest extends TestCase
{

	private $container;

	/** @var  MacroPresenter */
	private $macroPresenter;

	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}

	public function setup()
	{
		$this->macroPresenter = $this->container->getByType('Nette\Application\IPresenterFactory')->createPresenter('Macro:Macro');
		$this->macroPresenter->autoCanonicalize = false;
	}

	public function testCorrectInstance()
	{
		Assert::true($this->macroPresenter instanceof MacroPresenter);
	}

	public function testMacroAvailability()
	{
		$request = new Nette\Application\Request('Macro:Macro', 'GET', array('action' => 'default'));
		$response = $this->macroPresenter->run($request);
		$source = (string)$response->getSource();
		Assert::same($source, MacroInstaller::OUTPUT);
	}

}

$test = new MacroAvailabilityTest(getContainer());
$test->run();