<?php
namespace Gwa\Wpsh\Command;

use Gwa\Wpsh\Process\MysqlDump;
use Gwa\Wpsh\Process\MysqlImport;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class MysqlSyncCommand extends Command
{
  use Traits\LoadAliases;

  protected function configure()
  {
    $this
      ->setName('mysqlsync')
      ->setDescription('Syncs the database between two aliases.')
      ->setHelp('Syncs the database between two aliases.')
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
      sprintf('Do you want to import data from %s into %s? This will destroy all data in %s. (y/n)?', $source, $target, $target),
      false
    );

    if (!$helper->ask($input, $output, $question)) {
      return;
    }

    $filepath = $this->getTempDirPath() . '/' . time() . '_' . $source . '.sql';

    // Dump source DB to temp file
    $process = new MysqlDump($sourcealias);
    $process->setOutputFile($filepath);
    $process->execute();

    // Import temp file to target DB
    $process = new MysqlImport($targetalias);
    $process->setInputFile($filepath);
    $process->execute();

    // Clean up temp file
    unlink($filepath);

    // TODO Run post sync mysql file
  }
}
