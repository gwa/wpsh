<?php
namespace Gwa\Wpsh\Process;

use Gwa\Wpsh\Alias\Alias;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MysqlDump extends AbstractCommand
{
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $alias = $this->getAlias();

    return sprintf(
      'mysqldump -h %s -P %s -u %s --password=%s %s',
      $alias->getDBHost(),
      $alias->getDBPort(),
      $alias->getDBUser(),
      $alias->getDBPassword(),
      $alias->getDBDatabase()
    );
  }
}
