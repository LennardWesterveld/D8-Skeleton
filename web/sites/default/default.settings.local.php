<?php

$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => 'DB_NAME',
  'username' => 'DB_USER',
  'password' => 'DB_PASSWORD',
  'host' => 'localhost',
  'prefix' => '',
);

# Enable on development.
# $settings['container_yamls'][] = __DIR__ . '/services.development.yml';

# Random hash
$settings['hash_salt'] = '__RANDOM_HASH_SALT__';

$config_directories = array(
  CONFIG_ACTIVE_DIRECTORY => '../config/active/',
  CONFIG_STAGING_DIRECTORY => '../config/staging/',
  CONFIG_SYNC_DIRECTORY => '../config/sync/'
);
