<?php
namespace Gwa\Wpsh\Process\Runner;

use Symfony\Component\Process\Process;

class DefaultRunner implements RunnerContract
{
  /**
   * {@inheritdoc}
   */
  public function run(Process $process)
  {
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return $process->getOutput();
  }
}