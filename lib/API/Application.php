<?php
namespace API;

use Slim\Slim;

/**
 * Main application class for data checking
 */
class Application extends Slim
{
    public function validateUser($user = array(), $action = 'create')
    {
        $errors = array();
        
        $user = filter_var_array(
            $user,
            array(
                'id' => FILTER_SANITIZE_NUMBER_INT,
                'firstname' => FILTER_SANITIZE_STRING,
                'lastname' => FILTER_SANITIZE_STRING,
                'email' => FILTER_SANITIZE_EMAIL,
            ),
            false
        );
        
        
        switch ($action) {
            
            case 'create':
            default:
                if (empty($user['firstname'])) {
                    $errors['user'][] = array(
                        'field' => 'firstname',
                        'message' => 'First name cannot be empty'
                    );
                }
                if (empty($user['email'])) {
                    $errors['user'][] = array(
                        'field' => 'email',
                        'message' => 'Email address cannot be empty'
                    );
                } elseif (false === filter_var(
                    $user['email'],
                    FILTER_VALIDATE_EMAIL
                )) {
                        $errors['user'][] = array(
                            'field' => 'email',
                            'message' => 'Email address is invalid'
                        );
                } else {
                
                    // Test for unique email
                    $results = \ORM::forTable('users')
                        ->where('email', $user['email'])->count();
                    if ($results > 0) {
                        $errors['user'][] = array(
                            'field' => 'email',
                            'message' => 'Email address already exists'
                        );
                    }
                }
                break;
        }

        return $errors;
    }
}