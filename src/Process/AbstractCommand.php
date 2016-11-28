<?php
namespace Gwa\Wpsh\Process;

use Gwa\Wpsh\Alias\Alias;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class AbstractCommand
{
  /**
   * @var Alias $alias
   */
  private $alias;

  /**
   * @param Alias $alias
   */
  public function __construct(Alias $alias)
  {
    $this->alias = $alias;
  }

  /**
   * Executes the command.
   */
  public function execute()
  {
    $command = $this->build();

    if ($this->getAlias()->isRemote() && $this->doSshWrap()) {
      $command = $this->wrapAsSsh($command);
    }

    $process = new Process($command);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return $process->getOutput();
  }

  /**
   * @param string $command
   * @return string
   */
  private function wrapAsSsh($command)
  {
    $alias = $this->getAlias();

    $address = $alias->getUser() . '@' . $alias->getHost();

    $identityfile = $alias->getIdentityFilePath();
    $identity = $identityfile ? ' -i ' . $identityfile : '';

    return sprintf(
      'ssh %s%s \'%s\'',
      $address,
      $identity,
      $command
    );
  }

  /**
   * Should the command be ssh "wrapped" for remote aliases?
   *
   * return boolean
   */
  protected function doSshWrap()
  {
    return true;
  }

  /**
   * @return string
   */
  abstract public function build();

  /**
   * @return Alias
   */
  public function getAlias()
  {
    return $this->alias;
  }
}
