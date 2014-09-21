<?php
/**
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 */
namespace Test\HelperModule;

use Nette;
use App\HelperModule\presenters\HelperPresenter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class HelperAvailabilityTest extends TestCase
{

	private $container;

	/** @var  HelperPresenter */
	private $helperPresenter;

	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}

	public function setup()
	{
		$this->helperPresenter = $this->container->getByType('Nette\Application\IPresenterFactory')->createPresenter('Helper:Helper');
		$this->helperPresenter->autoCanonicalize = false;
	}

	public function testCorrectInstance()
	{
		Assert::true($this->helperPresenter instanceof HelperPresenter);
	}

	public function testMacroAvailability()
	{
		$request = new Nette\Application\Request('Helper:Helper', 'GET', array('action' => 'default'));
		$response = $this->helperPresenter->run($request);
		$source = (string)$response->getSource();
		Assert::same($source, 'helper1_test_text');
	}

}

$test = new HelperAvailabilityTest(getContainer());
$test->run();