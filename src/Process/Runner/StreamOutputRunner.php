<?php
namespace Gwa\Wpsh\Process\Runner;

use Symfony\Component\Process\Process;

class StreamOutputRunner implements RunnerContract
{
  /**
   * {@inheritdoc}
   */
  public function run(Process $process)
  {
    $process->run(function ($type, $buffer) {
      if (Process::ERR === $type) {
        echo 'ERR > '.$buffer;
      } else {
        echo $buffer;
      }
    });

    return '';
  }
}