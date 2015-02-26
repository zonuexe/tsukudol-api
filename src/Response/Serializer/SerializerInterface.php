<?php
namespace TsukudolAPI\Response\Serializer;

/**
 * Interface of Response serializer classes
 *
 * @package   TsukudolAPI\Response\Serializer
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
interface SerializerInterface
{
    /**
     * @param  mixed  $value
     * @return string
     */
    public function serialize($value);
}
