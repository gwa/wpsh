<?php
namespace Gwa\Wpsh\Process;

use Gwa\Wpsh\Process\Runner\MysqlDumpRunner;
use Symfony\Component\Process\Process;

class MysqlDump extends AbstractAliasCommand implements CommandContract
{
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $alias = $this->getAlias();

    $cmd = sprintf(
      'mysqldump -h %s -P %s -u %s --password="\"%s\"" %s',
      $alias->getDBHost(),
      $alias->getDBPort(),
      $alias->getDBUser(),
      $alias->getDBPassword(),
      $alias->getDBDatabase()
    );

    return $cmd;
  }

  /**
   * {@inheritdoc}
   */
  protected function getRunner()
  {
    return new MysqlDumpRunner;
  }
}
