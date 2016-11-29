<?php
namespace Gwa\Wpsh\Process;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GenericCommand implements CommandContract
{
  /**
   * @param string $command
   */
  private $command;

  /**
   * Executes the command.
   */
  public function execute()
  {
    $command = $this->build();

    $process = new Process($command);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return $process->getOutput();
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    return $this->command;
  }

  /**
   * @return string
   */
  public function getCommand()
  {
    return $this->command;
  }

  /**
   * @param string $command
   * @return self
   */
  public function setCommand($command)
  {
    $this->command = $command;
    return $this;
  }
}
