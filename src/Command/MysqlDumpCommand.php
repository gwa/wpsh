<?php
namespace Gwa\Wpsh\Command;

use Gwa\Wpsh\Process\MysqlDump;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MysqlDumpCommand extends Command
{
  use Traits\LoadAliases;

  protected function configure()
  {
    $this
      ->setName('mysqldump')
      ->setDescription('Dumps the database.')
      ->setHelp('Dumps the database.')
      ->addArgument('alias', InputArgument::REQUIRED, 'The site alias.');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $name = $input->getArgument('alias');
    $alias = $this->getAliasLoader()->getAlias($name);

    $process = new MysqlDump($alias);
    $output->write($process->execute());
  }
}
