# wpsh

A command line tool for local WordPress development.

## Introduction

`wpsh` is a command line tool we use to sync the database and uploaded files from a remote (PRODUCTION) WordPress install to a local (DEVELOPMENT) install.

It was inspired by Drupal's `drush` tool.

## Prerequisites

You will need:

* `composer` installed locally (for installation).
* `mysql` needs to be installed remotely.
* `mysql` and `rsync` need to be installed locally and in your path.
* SSH access to the remote server.

## Installation

Install globally using [composer](https://getcomposer.org).

```bash
$ composer global require gwa/wpsh
```

Test that `wpsh` is installed:

```bash
$ wpsh
```

You should see some usage help output.

## Setup

### Aliases

Site "aliases" contain the configuration for a site, either remote or local. `wpsh` looks for site aliases in `~/.wpsh`.

First create the directory in your HOME directory.

```bash
$ mkdir ~/.wpsh
```

Create a file called in `~/.wpsh` called `alias.[mysite].php`.

`mysite` can contain alphanumeric characters, hyphens and periods.

```php
<?php
// File: ~/.wpsh/alias.mysite.php
return [
  // The production server
  //
  '@mysite.prod' => [
    // SSH configuration
    'remote' => [
      'host'     => '123.255.12.123',
      'user'     => 'username',
      // Path to a SSH key file
      // 'identity' => '~/.ssh/custom_rsa',
    ],

    // Absolute paths
    'path' => [
      // The base directory
      'base'    => '/var/www/mysite',
      // Path to a custom MySQL cnf file
      'cnf'     => '~/mysql_cnf/mysite.cnf',
      // Path to a custom wp dir, relative to base
      // 'wp' => '',
      // Path to a custom uploads dir, relative to base
      // 'uploads' => 'wp-content/uploads',
    ],

    // Database settings
    'db' => [
      // Name of the database
      'database' => 'dbname'
    ],

    // Other settings
    'settings' => [
      'siteurl' => 'http://www.example.com',
      // Set a custom `home` path. Defaults to siteurl.
      // 'home' => 'http://www.example.com/wp',
    ]
  ],

  // The local development copy
  // Settings as above, but without `remote`
  //
  '@mysite.local' => [
    'path' => [
      'base'    => '/path/local/server/wordpress',
    ],

    'db' => [
      'database' => 'dbname',
    ],

    'settings' => [
      'siteurl' => 'http://mysite.local',
    ]
  ],
];
```

### MySQL cnf files

We use `cnf` files [to store MySQL credentials](http://dev.mysql.com/doc/refman/5.7/en/option-files.html).

You can save these in one of the default paths (see MySQL docs), or in a custom path that is defined in the site alias' `cnf` path.

Example:

```
[client]
user=mysqluser
password="pa$$word"
host=127.0.0.1
port=3306
```

## Usage

The following commands are available.

### `sa`

Lists available aliases.

```
$ wpsh sa
```

### `mysqldump`

Creates a mysql dump file.

```
$ wpsh mysqldump [@alias] > /path/to/outfile.sql
```

### `mysqlsync`

Syncs DB from @alias1 to @alias2.

Updates settings and URLs in posts with the local settings and URLs.

```
$ wpsh mysqlsync [@alias1] [@alias2]
```

### `rsync`

Syncs uploads from @alias1 to @alias2.

```
$ wpsh rsync [@alias1] [@alias2]
```

## TODOs

[ ] Provide hook mechanism for custom scripts / MySQL queries to be executed after sync.
