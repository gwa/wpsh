<?php
namespace Gwa\Wpsh\Command;

use Gwa\Wpsh\Alias\Alias;
use Gwa\Wpsh\Process\GenericCommand;
use Gwa\Wpsh\Process\Runner\StreamOutputRunner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class RsyncCommand extends Command
{
  use Traits\LoadAliases;

  protected function configure()
  {
    $this
      ->setName('rsync')
      ->setDescription('Syncs the uploads directory between two aliases.')
      ->setHelp('Syncs the uploads directory between two aliases.')
      ->addArgument('source', InputArgument::REQUIRED, 'The source site alias.')
      ->addArgument('target', InputArgument::REQUIRED, 'The target site alias.');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $source = $input->getArgument('source');
    $sourcealias = $this->getAliasLoader()->getAlias($source);
    $target = $input->getArgument('target');
    $targetalias = $this->getAliasLoader()->getAlias($target);

    // Get confirmation
    $helper = $this->getHelper('question');
    $question = new ConfirmationQuestion(
      sprintf('Do you want to sync the uploads directory from %s to %s? (y/n)?', $source, $target, $target),
      false
    );

    if (!$helper->ask($input, $output, $question)) {
      return;
    }

    $rsync = sprintf(
      'rsync -rztv%s %s %s',
      $this->getSshFlag($sourcealias, $targetalias),
      $this->getUserHostAndPath($sourcealias) . '/',
      $this->getUserHostAndPath($targetalias)
    );

    $command = new GenericCommand;
    $command->setCommand($rsync);
    $command->setRunner(new StreamOutputRunner);
    $command->execute();
  }

  /**
   * @return string
   */
  private function getSshFlag(Alias $sourcealias, Alias $targetalias)
  {
    if (!$sourcealias->isRemote() && !$targetalias->isRemote()) {
      return '';
    }

    $alias = $sourcealias->isRemote() ? $sourcealias : $targetalias;
    $identity = $alias->getIdentityFilePath();

    return $identity ? sprintf(' -e \'ssh -i %s\'', $identity) : '';
  }

  /**
   * @return string
   */
  private function getUserHostAndPath(Alias $alias)
  {
    if ($alias->isRemote()) {
      return sprintf(
        '%s@%s:%s',
        $alias->getUser(),
        $alias->getHost(),
        $alias->getPathUploads()
      );
    }

    return $alias->getPathUploads();
  }
}
