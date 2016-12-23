<?php
namespace Gwa\Wpsh\Alias;

use Gwa\Wpsh\Path\Home;

/**
 * The alias class represents a WP install, either local or remote.
 */
class Alias
{
  private $name;

  private $data;

  public function __construct($name, array $data)
  {
    $this->name = $name;
    $this->data = $data;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return boolean
   */
  public function isRemote()
  {
    return isset($this->data['remote']);
  }

  /**
   * @return string
   */
  public function getHost()
  {
    return $this->data['remote']['host'];
  }

  /**
   * @return string
   */
  public function getUser()
  {
    return $this->data['remote']['user'];
  }

  /**
   * @return string|NULL
   */
  public function getIdentityFilePath()
  {
    if (!isset($this->data['remote']['identity'])) {
      return NULL;
    }

    return str_replace('~', (new Home())->get(), $this->data['remote']['identity']);
  }

  /**
   * @return string|NULL
   */
  public function getCnfFilePath()
  {
    return isset($this->data['path']['cnf']) ? $this->data['path']['cnf'] : NULL;
  }

  // --------

  /**
   * @return string
   */
  public function getDatabase()
  {
    return isset($this->data['db']['database']) ? $this->data['db']['database'] : '';
  }

  // --------

  /**
   * @return string
   */
  public function getPathBase()
  {
    return isset($this->data['path']['base']) ? $this->data['path']['base'] : '';
  }

  /**
   * @return string
   */
  public function getPathUploads()
  {
    $relative = isset($this->data['path']['uploads']) ? $this->data['path']['uploads'] : 'wp-content/uploads';
    return $this->getPathBase() . '/' . $relative;
  }

  /**
   * @return string
   */
  public function getPathWp()
  {
    $relative = isset($this->data['path']['wp']) ? $this->data['path']['wp'] : '';
    return rtrim($this->getPathBase() . '/' . $relative, '/');
  }
}
