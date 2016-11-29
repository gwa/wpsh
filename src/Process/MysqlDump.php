<?php
namespace Gwa\Wpsh\Process;

class MysqlDump extends AbstractAliasCommand implements CommandContract
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
