<?php
namespace TsukudolAPI\Response;

/**
 * Serialized data Response class
 *
 * @package   TsukudolAPI\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
final class SerializedResponse implements ResponseInterface
{
    const CONTENT_TYPE = 'application/javascript; charset=utf-8';

    /** @var array */
    public $value;
    /** @var \TsukudolAPI\Response\Serializer\SerializerInterface */
    public $serializer;

    /**
     * @param array $value
     * @param int   $json_encode_option
     * @link  http://php.net/manual/json.constants.php
     */
    public function __construct(array $value, Serializer\SerializerInterface $serializer)
    {
        $this->value = $value;
        $this->serializer = $serializer;
    }

    /**
     * @return string
     */
    public function getContentType(\TsukudolAPI\Application $app)
    {
        return self::CONTENT_TYPE;
    }

    /**
     * @return string
     */
    public function render(\TsukudolAPI\Application $app)
    {
        return $this->serializer->serialize($this->value);
    }
}
