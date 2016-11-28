#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Gwa\Wpsh\Command\MysqlDumpCommand;
use Gwa\Wpsh\Command\ShowAliasesCommand;
use Symfony\Component\Console\Application;

$application = new Application;

// Register commands
$application->add(new ShowAliasesCommand);
$application->add(new MysqlDumpCommand);

$application->run();
