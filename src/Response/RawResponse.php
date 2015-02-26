<?php
namespace TsukudolAPI\Response;

final class RawResponse implements ResponseInterface
{
    /** @var string */
    public $content;

    /** @var string */
    public $content_type;

    /**
     * @param string $content
     * @param string $content_type
     */
    public function __construct($content, $content_type)
    {
        $this->content = $content;
        $this->content_type = $content_type;
    }

    /**
     * @return string
     */
    public function getContentType(\TsukudolAPI\Application $_)
    {
        return $this->content_type;
    }

    /**
     * @return string
     */
    public function render(\TsukudolAPI\Application $_)
    {
        return $this->content;
    }
}
