<?php
namespace Gwa\Wpsh\Command\Traits;

use Gwa\Wpsh\Alias\Loader;
use Gwa\Wpsh\Path\Home;

trait LoadAliases
{
  /**
   * @var string
   */
  private $homepath;

  /**
   * @var Loader
   */
  private $loader;

  /**
   * @return Loader
   */
  private function getAliasLoader()
  {
    if (!isset($this->loader)) {
      $this->loader = new Loader;
      $path = $this->getHomePath() . '/.wpsh';

      try {
        $this->getAliasLoader()->load($path);
      } catch (\Exception $exception) {
        $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
        exit;
      }
    }

    return $this->loader;
  }

  /**
   * @param string $name
   * @return array|NULL
   */
  private function getAlias($name)
  {
    return $this->getLoader()->getAlias($name);
  }

  /**
   * @return string
   */
  private function getHomePath()
  {
    if (!isset($this->homepath)) {
      $this->homepath = (new Home())->get();
    }

    return $this->homepath;
  }

  /**
   * @return string
   */
  private function getTempDirPath()
  {
    $path = $this->getHomePath() . '/.wpsh/temp';

    if (!file_exists($path)) {
      mkdir($path);
    }

    return $path;
  }
}
