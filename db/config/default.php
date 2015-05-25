<?php

/**
 * Db Confirgation for sqlite
 */
$config['db'] = array(
    'driver' => 'sqlite',
    'dbname' => 'test.sqlite',
    'dbpath' => realpath(__DIR__ . '/../db_file')
);

$config['db']['dsn'] = sprintf(
    '%s:%s/%s',
    $config['db']['driver'],
    $config['db']['dbpath'],
    $config['db']['dbname']
);

// Cache TTL in seconds
$config['app']['cache.ttl'] = 60;