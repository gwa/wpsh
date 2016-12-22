<?php
namespace Gwa\Wpsh\Process;

use Gwa\Wpsh\Process\Runner\DefaultRunner;
use Gwa\Wpsh\Process\Runner\RunnerContract;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GenericCommand implements CommandContract
{
  /**
   * @var RunnerContract $runner
   */
  protected $runner;

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
    $runner = $this->getRunner();

    return $runner->run($process);
  }

  /**
   * @return RunnerContract
   */
  protected function getRunner()
  {
    return isset($this->runner) ? $this->runner : new DefaultRunner;
  }

  /**
   * @param RunnerContract $runner
   */
  public function setRunner(RunnerContract $runner)
  {
    $this->runner = $runner;
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
