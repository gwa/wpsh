<?php
namespace Gwa\Wpsh\Process\Runner;

use Symfony\Component\Process\Process;

interface RunnerContract
{
  /**
   * @param Process $process
   * @return string
   */
  public function run(Process $process);
}