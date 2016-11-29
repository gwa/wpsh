<?php
namespace Gwa\Wpsh\Process;

class MysqlImport extends AbstractCommand
{
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $alias = $this->getAlias();

    return sprintf(
      'mysql -h %s -P %s -u %s --password=%s %s',
      $alias->getDBHost(),
      $alias->getDBPort(),
      $alias->getDBUser(),
      $alias->getDBPassword(),
      $alias->getDBDatabase()
    );
  }
}
