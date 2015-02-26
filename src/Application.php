<?php
namespace TsukudolAPI;

/**
 * Tsukudol API Application
 *
 * @package   TsukudolAPI\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 *
 * @property-read array $server $_SERVER
 * @property-read array $cookie $_COOKIE
 * @property-read array $get    $_GET
 * @property-read array $post   $_POST
 */
final class Application
{
    /** @var array $_SERVER */
    private $server;
    /** @var array $_COOKIE */
    private $cookie;
    /** @var array $_GET */
    private $get;
    /** @var array $_POST */
    private $post;
    /** @var int */
    private $status_code = 200; // '200 OK'
    /** @var \DateTimeImmutable */
    private $now;

    /** @var string */
    private $view_mode;

    public function __get($name) { return $this->$name; }

    /**
     * @param array $_SERVER
     */
    public function __construct(array $server, array $cookie, array $get, array $post, \DateTimeImmutable $now)
    {
        $this->server  = $server;
        $this->cookie  = $cookie;
        $this->get     = $get;
        $this->post    = $post;
        $this->now     = $now;
    }

    public function execute(\Teto\Routing\Action $action)
    {
        list($controller_name, $method) = $action->value;
        $controller = "\\TsukudolAPI\\Controller\\$controller_name";

        try {
            $response = (new $controller($this))->$method($action);
        } catch (\Exception $e) {
            $controller = new \TsukudolAPI\Controller\IndexController($this);
            $response = $controller->display500($action);
        }

        return $response;
    }

    /**
     * @param int  $status
     */
    public function setHttpStatus($status)
    {
        $this->status_code = $status;
    }

    /**
     * @return boolean
     */
    public function sendHttpStatus()
    {
        if (!headers_sent() && $this->status_code) {
            http_response_code($this->status_code);
            return true;
        }

        return false;
    }

    public function renderResponse(\TsukudolAPI\Response\ResponseInterface $response)
    {
        if (!headers_sent()) {
            header("Content-type: " . $response->getContentType($this));
        }

        return $response->render($this);
    }

    /**
     * @return array
     */
    public static function getRoutingMap()
    {
        $re_team   = '/^2zicon$/';
        $re_member = '/^@?\W{1,31}$/';

        $routing_map = [
            ['GET',  '/',                    ['IndexController', 'index']],
            ['GET',  '/license',             ['IndexController', 'license']],
            ['GET',  '/:team',               ['TeamController',  'team'],
                      ['team' => $re_team] ],
            ['GET',  '/:team/next_birthday', ['TeamController',  'next_birthday'],
                      ['team' => $re_team, '?ext' => ['', 'json'] ] ],
            ['GET',  '/:team/:member',       ['TeamController',  'team_member'],
                      ['team' => $re_team], 'member' => $re_member ],
             '#404'     =>                   ['IndexController', 'display404'],
        ];

        return $routing_map;
    }
}
