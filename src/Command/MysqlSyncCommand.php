<?php
namespace Gwa\Wpsh\Command;

use Gwa\Wpsh\Alias\Alias;
use Gwa\Wpsh\Process\MysqlDump;
use Gwa\Wpsh\Process\MysqlImport;
use Gwa\Wpsh\Process\MysqlQuery;

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
    $output->writeln('<info>Dumping source DB...</info>');
    $process = new MysqlDump($sourcealias);
    $process->setOutputFile($filepath);
    $process->execute();

    // Import temp file to target DB
    $output->writeln('<info>Importing dump to target DB...</info>');
    $process = new MysqlImport($targetalias);
    $process->setInputFile($filepath);
    $process->execute();

    // Clean up temp file
    unlink($filepath);

    // Update settings
    $this->updateSettings($targetalias, $output);

    // Update URLs in posts
    $this->updatePostURLs($sourcealias, $targetalias, $output);

    // TODO Run post sync mysql file
  }

  private function updateSettings(Alias $targetalias, OutputInterface $output)
  {
    $siteurl = $targetalias->getSiteURL();
    $home = $targetalias->getHome();

    if ($siteurl) {
      $output->writeln('<info>Updating option: siteurl.</info>');
      $this->runQuery(
        $targetalias,
        sprintf('UPDATE wp_options SET option_value = "%s" WHERE option_name = "siteurl"', addslashes($siteurl))
      );
    }

    if ($home) {
      $output->writeln('<info>Updating option: home.</info>');
      $this->runQuery(
        $targetalias,
        sprintf('UPDATE wp_options SET option_value = "%s" WHERE option_name = "home"', addslashes($home))
      );
    }
  }

  private function updatePostURLs(Alias $sourcealias, Alias $targetalias, OutputInterface $output)
  {
    $sourceurl = $sourcealias->getSiteURL();
    $targeturl = $targetalias->getSiteURL();

    if (!$sourceurl || !$targeturl) {
      return;
    }
    
    $output->writeln('<info>Updating URLs in posts.</info>');
      
    $this->runQuery(
      $targetalias,
      sprintf('UPDATE wp_posts SET post_content = replace(post_content, "%s", "%s")', addslashes($sourceurl), addslashes($targeturl))
    );
  }

  private function runQuery(Alias $targetalias, $query)
  {
    $process = new MysqlQuery($targetalias);
    $process->setQuery($query);
    $process->execute();
  }
}
