<?php
namespace Gwa\Wpsh\Process;

/**
 * Runs an arbitrary MySQL query.
 */
class MysqlQuery extends AbstractAliasCommand implements CommandContract
{
  /**
   * @param string $query
   */
  private $query;

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $alias = $this->getAlias();
    $extrafile = $alias->getCnfFilePath() ? sprintf('--defaults-extra-file=%s ', $alias->getCnfFilePath()) : '';

    return sprintf(
      'mysql %s--database=%s -e \'%s\'',
      $extrafile,
      $alias->getDatabase(),
      $this->getQuery()
    );
  }

  /**
   * @return string
   */
  public function getQuery()
  {
    return $this->query;
  }

  /**
   * @param string $query
   * @return self
   */
  public function setQuery($query)
  {
    $this->query = $query;
    return $this;
  }
}
