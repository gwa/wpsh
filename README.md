# wpsh

A command line tool for local WordPress development.

## Setup

### Aliases

Set up site aliases in `~/.wpsh/`.

```php
<?php
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
  ],
];
```

### MySQL cnf files

We use `cnf` files [to store MySQL credentials](http://dev.mysql.com/doc/refman/5.7/en/option-files.html).

Example:

```
[client]
user=mysqluser
password="pa$$word"
host=127.0.0.1
port=3306
```

## Usage

### `sa`

Lists available aliases.

```
php -f wpsh.php sa
```

### `mysqldump`

Creates a mysql dump file.

```
php -f wpsh.php mysqldump [@alias] > /path/to/outfile.sql
```

### `mysqlsync`

Syncs DB from @alias1 to @alias2.

```
php -f wpsh.php mysqlsync [@alias1] [@alias2]
```

### `rsync`

Syncs uploads from @alias1 to @alias2.

```
php -f wpsh.php rsync [@alias1] [@alias2]
```
