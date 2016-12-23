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
    $extrafile = $alias->getCnfFilePath() ? sprintf('--defaults-extra-file=%s ', $alias->getCnfFilePath()) : '';

    $cmd = sprintf(
      'mysqldump %s%s',
      $extrafile,
      $alias->getDatabase()
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
