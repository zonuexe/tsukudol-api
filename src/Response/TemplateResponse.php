<?php
namespace TsukudolAPI\Response;
use Baguette;

/**
 * HTML Template Response
 *
 * @package   TsukudolAPI\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
final class TemplateResponse extends Baguette\Response\TwigResponse
{
    /**
     * @param  Baguette\Application $app
     * @return string
     */
    public function render(Baguette\Application $app)
    {
        $params = $this->params + [
            'server'  => $app->server,
            'cookie'  => $app->cookie,
            'get'     => $app->get,
            'post'    => $app->post,
            'now'     => $app->now,
        ];

        return static::$twig->render($this->tpl_name.'.tpl.html', $params);
    }
}
