<?php
namespace Gwa\Wpsh\Command\Traits;

use Gwa\Wpsh\Alias\Loader;
use Gwa\Wpsh\Path\Home;

trait LoadAliases
{
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
      $path = (new Home())->get() . '/.wpsh';

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
}
