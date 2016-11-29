<?php
namespace Gwa\Wpsh\Process;

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

    return sprintf(
      'mysql -h %s -P %s -u %s --password=%s %s -e "%s"',
      $alias->getDBHost(),
      $alias->getDBPort(),
      $alias->getDBUser(),
      $alias->getDBPassword(),
      $alias->getDBDatabase(),
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
