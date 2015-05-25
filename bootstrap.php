<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';

use Slim\Slim;
use API\Application;
use API\Middleware\TokenOverBasicAuth;

// Init and load config file
$config = array();

require_once dirname(__FILE__) . '/db/config/default.php';

// Create Application
$app = new Application($config['app']);

// Get log writer
$log = $app->getLog();

// Init database
try {
    
  \ORM::configure($config['db']['dsn']);
  
} catch (\PDOException $e) {
    $log->error($e->getMessage());
}

// Parses JSON body
$app->add(new \Slim\Middleware\ContentTypes());

// JSON Middleware
$app->add(new API\Middleware\JSON('/api/v1'));

// Auth Middleware (outer)
$app->add(new API\Middleware\TokenOverBasicAuth(array('root' => '/api/v1')));