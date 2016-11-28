<?php
namespace Gwa\Wpsh\Alias;

use Gwa\Wpsh\Alias\Alias;
use Symfony\Component\Filesystem\Filesystem;

class Loader
{
  const ERR_PATH_NOT_EXIST = 'path not exist';
  const ERR_PATH_NOT_DIRECTORY = 'path not a directory';

  /**
   * @param array $aliases
   */
  private $aliases = [];

  public function load($path)
  {
    $aliases = [];

    $fs = new Filesystem;

    if (!$fs->exists($path)) {
      throw new \Exception(self::ERR_PATH_NOT_EXIST);
    }

    // Traverse files in directory
    $files = scandir($path);

    if (!is_array($files)) {
      throw new \Exception(self::ERR_PATH_NOT_DIRECTORY);
    }

    $pattern = '/^alias\.[a-z0-9\-_\.]+\.php$/';

    foreach ($files as $file) {
      $filepath = realpath($path . '/' . $file);

      if (!is_file($filepath) || !preg_match($pattern, $file)) {
        continue;
      }

      foreach (require($filepath) as $name => $data) {
        $aliases[$name] = new Alias($name, $data);
      }
    }

    $this->aliases = $aliases;
  }

  /**
   * @return array
   */
  public function getAliasNames()
  {
    return array_keys($this->aliases);
  }

  /**
   * @param string $name
   * @return array|NULL
   */
  public function getAlias($name)
  {
    return array_key_exists($name, $this->aliases) ? $this->aliases[$name] : NULL;
  }
}
