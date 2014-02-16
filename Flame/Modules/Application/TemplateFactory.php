<?php
/**
 * Class PresenterTemplateFactory
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 23.08.13
 */

namespace Flame\Modules\Application;

use Nette\Application\UI\Presenter;

trait TemplateFactory
{

	/**
	 * @param null $class
	 * @return \Nette\Templating\ITemplate
	 */
	public function createTemplate($class = null)
	{
		$presenter = $this->getPresenter(FALSE);
		$template = $presenter->getContext()->createServiceNette__Template();
		$template->onPrepareFilters[] = $this->templatePrepareFilters;

		// default parameters
		$template->control = $template->_control = $this;
		$template->presenter = $template->_presenter = $presenter;
		if ($presenter instanceof Presenter) {
			$template->setCacheStorage($presenter->getContext()->getService('nette.templateCacheStorage'));
			$template->user = $presenter->getUser();
			$template->netteHttpResponse = $presenter->getContext()->getByType('Nette\Http\Response');
			$template->netteCacheStorage = $presenter->getContext()->getByType('Nette\Caching\IStorage');
			$template->baseUri = $template->baseUrl = rtrim($presenter->getContext()->getByType('Nette\Http\Request')->getUrl()->getBaseUrl(), '/');
			$template->basePath = preg_replace('#https?://[^/]+#A', '', $template->baseUrl);

			// flash message
			if ($presenter->hasFlashSession()) {
				$id = $this->getParameterId('flash');
				$template->flashes = $presenter->getFlashSession()->$id;
			}
		}
		if (!isset($template->flashes) || !is_array($template->flashes)) {
			$template->flashes = array();
		}

		return $template;
	}

}