<?php

/**
 * Auth middleware for user and apikey check for api users
 */
namespace API\Middleware;

class TokenOverBasicAuth extends \Slim\Middleware
{
    /**
     * @var array
     */
    protected $settings = array(
        'realm' => 'Protected Area',
        'root'  => '/'
    );

    /**
     * Constructor
     *
     * @param   array  $config   Configuration and Login Details
     * @return  void
     */
    public function __construct(array $config = array())
    {
        if (!isset($this->app)) {
            $this->app = \Slim\Slim::getInstance();
        }
        $this->config = array_merge($this->settings, $config);
    }

    /**
     * Call
     *
     * This method will check the HTTP request headers for 
     * previous authentication. If the request has already authenticated,
     * the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     *
     * @return  void
     */
    public function call()
    {
        $req = $this->app->request();
        $res = $this->app->response();

        if (preg_match(
            '|^' . $this->config['root'] . '.*|',
            $req->getResourceUri()
        )) {
        
            // We just need the user
            $authUser = $req->headers('PHP_AUTH_USER');
            $authPass = $req->headers('PHP_AUTH_PW');

            if (!($authUser && $this->verify($authUser, $authPass))) {
                $res->status(401);
                $res->header(
                    'WWW-Authenticate',
                    sprintf('Basic realm="%s"', $this->config['realm'])
                );
                $res->body(json_encode(array("message" => 'invalid token!!')));
                return;
            }
        }
        
        $this->next->call();
    }
    
    /**
     * Check passed auth token
     *
     * @param string $authToken
     * @return boolean
     */
    protected function verify($authUser, $authPass)
    {
        $api_user = \ORM::forTable('api_auth')->where(
            array('email'=> $authUser, 
                  'apikey'=> $authPass))->findOne();

        if (false !== $api_user) {
            $this->app->user = $api_user->asArray();
            return true;
        }
        
        return false;
    }
}
