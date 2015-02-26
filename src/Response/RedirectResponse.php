<?php
namespace TsukudolAPI\Response;

/**
 * Redirect Response class
 *
 * @package   TsukudolAPI\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
final class RedirectResponse implements ResponseInterface
{
    private static $REDIRECT_STATUS_CODES = [
        301,
        302,
    ];

    /** @var string */
    private $location;
    /** @var array  */
    private $params;
    /** @var int */
    private $status_code;

    /**
     * @param string $location
     * @param array  $params
     */
    public function __construct($location, $params = [], $status_code = 301)
    {
        $this->location    = $location;
        $this->params      = $params;
        $this->status_code = $status_code;
    }

    /**
     * @return string
     */
    public function getContentType(\TsukudolAPI\Application $_)
    {
        return null;
    }

    /**
     * @return string
     */
    public function render(\TsukudolAPI\Application $app)
    {
        header('Location: ' . $this->location, true, $this->status_code);
    }
}
