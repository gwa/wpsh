<?php
namespace Gwa\Wpsh\Process;

use Gwa\Wpsh\Alias\Alias;
use Gwa\Wpsh\Process\Runner\DefaultRunner;
use Symfony\Component\Process\Process;

/**
 * An command that acts upon an Alias.
 */
abstract class AbstractAliasCommand
{
  /**
   * @var Alias $alias
   */
  private $alias;

  /**
   * @var string $inputfile
   */
  private $inputfile;

  /**
   * @var string $outputfile
   */
  private $outputfile;

  /**
   * @param Alias|NULL $alias
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

    $command = $this->appendInputFile($command);
    $command = $this->appendOutputFile($command);

    $process = new Process($command);
    $runner = $this->getRunner();

    return $runner->run($process);
  }

  /**
   * @return RunnerContract
   */
  protected function getRunner()
  {
    return new DefaultRunner;
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

    $cmd = sprintf(
      'ssh %s%s \'%s\'',
      $address,
      $identity,
      $command
    );

    return $cmd;
  }

  /**
   * @param string $command
   * @return string
   */
  private function appendOutputFile($command)
  {
    if (!$this->outputfile) {
      return $command;
    }

    return sprintf('%s > %s', $command, $this->outputfile);
  }

  /**
   * @param string $filepath
   * @return self
   */
  public function setOutputFile($filepath)
  {
    $this->outputfile = $filepath;
    return $this;
  }

  /**
   * @return string
   */
  public function getOutputFile()
  {
    return $this->outputfile;
  }

  /**
   * @param string $command
   * @return string
   */
  private function appendInputFile($command)
  {
    if (!$this->inputfile) {
      return $command;
    }

    return sprintf('%s < %s', $command, $this->inputfile);
  }

  /**
   * @param string $filepath
   * @return self
   */
  public function setInputFile($filepath)
  {
    $this->inputfile = $filepath;
    return $this;
  }

  /**
   * @return string
   */
  public function getInputFile()
  {
    return $this->inputfile;
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
