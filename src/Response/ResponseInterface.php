<?php
namespace TsukudolAPI\Response;

/**
 * Interface of Response classes
 *
 * @package   TsukudolAPI\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
interface ResponseInterface
{
    /** @return string */
    public function getContentType(\TsukudolAPI\Application $app);

    /** @return string */
    public function render(\TsukudolAPI\Application $app);
}
