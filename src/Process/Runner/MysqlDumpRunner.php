<?php
namespace Gwa\Wpsh\Process\Runner;

use Symfony\Component\Process\Process;

class MysqlDumpRunner implements RunnerContract
{
  /**
   * {@inheritdoc}
   */
  public function run(Process $process)
  {
    $process->start();

    $warning = 'Warning: Using a password on the command line';

    foreach ($process as $type => $data) {
      // Supress password warning
      if (substr($data, 0, strlen($warning)) !== $warning) {
        echo $data;
      }
    }

    return '';
  }
}