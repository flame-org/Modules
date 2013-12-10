<?php
/**
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 */
namespace Flame\Modules\Config;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\Neon;

class NeonFile implements IConfigFile {


	/** @var string */
	private $path;

	/**@var string */
	private $section;

	/** @var \Nette\Caching\Cache */
	private $cache;

	/**
	 * @param string $path
	 * @param string $cacheDir
	 * @param string|NULL $section
	 * @throws InvalidArgumentException
	 */
	public function __construct($path, $cacheDir = NULL, $section = 'modules') {
		if (!file_exists($path)) {
			throw new InvalidArgumentException('Given config path "' . $path . '" does not exists.');
		}

		if ($cacheDir !== NULL) {
			$this->cache = new Cache(new FileStorage($cacheDir), __CLASS__);
		}

		$this->path = $path;
		$this->section = $section;
	}

	/**
	 * @return array
	 * @throws InvalidStateException
	 */
	public function getConfig()
	{
		if (!$this->cache)
			return $this->load();

		$key = array($this->path, $this->section);

		if ($result = $this->cache->load($key)) {
			return $result;
		}

		$result = $this->load();
		$this->cache->save($key, $result, array(
			Cache::FILES => $this->path
		));

		return $result;
	}

	/**
	 * @return array
	 * @throws \Nette\InvalidStateException
	 */
	protected function load()
	{
		$config = Neon::decode(file_get_contents($this->path));

		if (!isset($config[$this->section])) {
			throw new InvalidStateException('Missing section ' . $this->section . ' in configuration file.');
		}

		return $config[$this->section];
	}

}