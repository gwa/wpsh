<?php
namespace Gwa\Wpsh\Path;

class Home
{
  /**
   * Returns the user's home directory path.
   * @return string
   */
  function get()
  {
    $home = getenv('HOME');

    if (!empty($home)) {
      // home should never end with a trailing slash.
      $home = rtrim($home, '/');
    } elseif (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
      // home on windows
      $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
      // If HOMEPATH is a root directory the path can end with a slash. Make sure
      // that doesn't happen.
      $home = rtrim($home, '\\/');
    }

    return empty($home) ? NULL : $home;
  }
}
