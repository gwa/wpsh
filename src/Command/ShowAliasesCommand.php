<?php
namespace Gwa\Wpsh\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowAliasesCommand extends Command
{
  use Traits\LoadAliases;

  protected function configure()
  {
    $this
      ->setName('sa')
      ->setDescription('Lists site aliases.')
      ->setHelp('Lists site aliases.');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    foreach ($this->getAliasLoader()->getAliasNames() as $name) {
      $output->writeln($name);
    }
  }
}
