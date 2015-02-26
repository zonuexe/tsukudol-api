<?php
namespace TsukudolAPI\Response;

/**
 * HTML Template Response
 *
 * @package   TsukudolAPI\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
final class TemplateResponse implements ResponseInterface
{
    const CONTENT_TYPE_HTML  = 'text/html; charset=utf-8';

    /** @var string */
    public $tpl_name;

    /** @var array */
    public $params;

    /** @var string|null */
    public $content_type;

    /** @var \Twig_Environment */
    private static $twig;

    /**
     * @param string $tpl_name
     * @param array  $params
     */
    public function __construct($tpl_name, array $params = [])
    {
        $this->tpl_name = $tpl_name;
        $this->params   = $params;
    }

    /**
     * @return string
     */
    public function getContentType(\TsukudolAPI\Application $app)
    {
        return self::CONTENT_TYPE_HTML;
    }

    /**
     * @return string
     */
    public function render(\TsukudolAPI\Application $app)
    {
        if (!self::$twig) { self::$twig = self::initTwig(); }

        return self::$twig->render($this->tpl_name.'.tpl.html', $this->params);
    }

    /**
     * @return \Twig_Environment
     */
    public static function initTwig()
    {
        $basedir = dirname(dirname(__DIR__));

        $twig_option = [
            'cache' => $basedir . '/cache/twig',
            'debug' => true,
        ];

        $loader = new \Twig_Loader_Filesystem($basedir . '/src/View/twig');
        $twig   = new \Twig_Environment($loader, $twig_option);

        return $twig;
    }
}
