<?php
namespace TsukudolAPI\Response\Serializer;

/**
 * JSON Serializer based on PHP JSON module
 *
 * @package   TsukudolAPI\Response\Serializer
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   AGPL-3.0 http://www.gnu.org/licenses/agpl-3.0.html
 */
final class PhpJsonSerializer implements SerializerInterface
{
    const CONTENT_TYPE = 'application/json; charset=utf-8';

    /** @var int */
    public $json_encode_option;

    /** @var boolean */
    public $empty_as_object;

    public function __construct()
    {
        $this->json_encode_option
            = isset($json_encode_options) ? $json_encode_option
            : JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ;
    }

    /**
     * @param  boolean      $value
     * @return JsonResponse $this
     */
    public function setEmptyAsObject($value = true)
    {
        $this->empty_as_object = $value;

        return $this;
    }

    /** @return string */
    public function getContentType()
    {
        return self::CONTENT_TYPE;
    }

    /** @return string */
    public function serialize($value)
    {
        if ($this->empty_as_object && empty($value)) {
            return json_encode([], $this->json_encode_option | JSON_FORCE_OBJECT);
        }

        return json_encode($value, $this->json_encode_option);
    }
}
