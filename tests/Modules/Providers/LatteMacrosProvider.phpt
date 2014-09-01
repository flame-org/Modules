<?php

namespace Flame\Tests\Modules\Providers;

use Flame\Tester\TestCase;
use Nette;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class ControlMock extends Nette\Application\UI\Control
{

}


/**
 * @author Ondřej Záruba
 */
class LatteMacrosProviderTest extends TestCase
{
	public function testLatteMacrosProvider()
	{
		/** @var Nette\Application\UI\ITemplateFactory $template */
		$template = $this->getContext()->getByType('Nette\Application\UI\ITemplateFactory')
			->createTemplate(new ControlMock());
		$template->setFile(__DIR__ . '/../files/macro.latte');

		Assert::same('<div id="id_test"></div>', trim((string) $template));
	}
}

\run(new LatteMacrosProviderTest(getContainer()));
