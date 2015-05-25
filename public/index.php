<?php
require_once dirname(__FILE__) . '/../bootstrap.php';

use API\Middleware\TokenOverBasicAuth;
use API\Exception;
use API\Exception\ValidationException;

// General API group
$app->group(
    '/api',
    function () use ($app, $log) {

        // Main Get request
        $app->get('/', function () {
            echo "<h1>API root end-point</h1>";
        });

        
      // Group for API Version 1
      $app->group(
          '/v1',
          // API Methods
          function () use ($app, $log) {

            // Get users
            $app->get(
              '/users',
              function () use ($app, $log) {

                $users = array();

                // Default resultset
                $results = \ORM::forTable('users');
                $users = $results->findArray();
                $total = count($users);

                $app->response->headers->set('X-Total-Count', $total);

                echo json_encode($users, JSON_PRETTY_PRINT);
              }
            );


            // Get users with ID
            $app->get(
                '/users/:id',
                function ($id) use ($app, $log) {

                  $id = filter_var(
                      filter_var($id, FILTER_SANITIZE_NUMBER_INT),
                      FILTER_VALIDATE_INT
                  );

                  if (false === $id) {
                      throw new ValidationException("Invalid contact ID");
                  }

                  $user = \ORM::forTable('users')->findOne($id);
                  if ($user) {

                    $output = $user->asArray();

                    echo json_encode($output, JSON_PRETTY_PRINT);
                    return;
                  }
                  $app->notFound();
                }
              );

                
              // Adds new contact
              $app->post(
                '/users',
                function () use ($app, $log) {

                  $body = $app->request()->getBody();
                  
                  if (!is_array($body)){
                    parse_str($body, $body_array);
                    $body = $body_array;
                  }
                  
                  $errors = $app->validateUser($body);

                  if (empty($errors)) {
                      $user = \ORM::for_table('users')->create();

                      $user->set($body);
                      
                      
                      if (true === $user->save()) {

                          $output = $user->asArray();

                          echo json_encode($output, JSON_PRETTY_PRINT);
                      } else {
                          echo json_encode("Unable to save user", JSON_PRETTY_PRINT);
                      }

                  } else {
                    echo json_encode($errors, JSON_PRETTY_PRINT);
                  }
                }
              );

                
              // Delete user with ID
              $app->delete(
                '/users/:id',
                function ($id) use ($app, $log) {

                  $user = \ORM::forTable('users')->findOne($id);

                  if ($user) {
                      $user->delete();
                      $app->halt(204);
                  }
                  $app->notFound();
                }
              );
            }
        );
    }
);

// Public human readable home page
$app->get(
    '/',
    function () use ($app, $log) {
        echo "<h1>Hello, this is a very basic RESTful Api Sample</h1>";
    }
);

/// Custom 404 error
$app->notFound(function () use ($app) {

    $mediaType = $app->request->getMediaType();

    $isAPI = (bool) preg_match('|^/api/v.*$|', $app->request->getPath());


    if ('application/json' === $mediaType || true === $isAPI) {

        $app->response->headers->set(
            'Content-Type',
            'application/json'
        );

        echo json_encode(
            array(
                'code' => 404,
                'message' => 'Not found'
            ),
            JSON_PRETTY_PRINT
        );

    } else {
        echo '<html><head><title>404 Page Not Found</title></head>
        <body><h1>404 Page Not Found</h1></body></html>';
    }
});

$app->run();