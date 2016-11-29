#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Gwa\Wpsh\Command\MysqlDumpCommand;
use Gwa\Wpsh\Command\MysqlSyncCommand;
use Gwa\Wpsh\Command\RsyncCommand;
use Gwa\Wpsh\Command\ShowAliasesCommand;
use Symfony\Component\Console\Application;

$application = new Application;

// Register commands
$application->add(new MysqlDumpCommand);
$application->add(new MysqlSyncCommand);
$application->add(new RsyncCommand);
$application->add(new ShowAliasesCommand);

$application->run();
