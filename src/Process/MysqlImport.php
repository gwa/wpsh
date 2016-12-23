<?php
namespace Gwa\Wpsh\Process;

class MysqlImport extends AbstractAliasCommand implements CommandContract
{
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $alias = $this->getAlias();
    $extrafile = $alias->getCnfFilePath() ? sprintf('--defaults-extra-file=%s ', $alias->getCnfFilePath()) : '';

    return sprintf(
      'mysql %s--database=%s',
      $extrafile,
      $alias->getDatabase()
    );
  }
}
