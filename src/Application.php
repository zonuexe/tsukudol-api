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
final class Application extends \Baguette\Application
{
    public function __get($name) { return $this->$name; }

    /**
     * @param  \Teto\Routing\Action $action
     * @return \Baguette\Response\ResponseInterface
     */
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
     * @return array
     */
    public static function getRoutingMap()
    {
        $re_team   = '/^2zicon$/';
        $re_member = '/^@?.{1,31}$/';

        $routing_map = [
            ['GET',  '/',                    ['IndexController', 'index']],
            ['GET',  '/license',             ['IndexController', 'license']],
            ['GET',  '/:team',               ['TeamController',  'team'],
                      ['team' => $re_team, '?ext' => ['', 'json']] ],
            ['GET',  '/:team/next_birthday', ['TeamController',  'next_birthday'],
                      ['team' => $re_team, '?ext' => ['', 'json']] ],
            ['GET',  '/:team/:member',       ['TeamController',  'team_member'],
                      ['team' => $re_team, 'member' => $re_member, '?ext' => ['', 'json']] ],
             '#404'     =>                   ['IndexController', 'display404'],
        ];

        return $routing_map;
    }
}
