<?php
namespace TsukudolAPI\Controller;
use Teto\Routing\Action;
use Baguette\Response\RawResponse;
use TsukudolAPI\Response\TemplateResponse;

final class IndexController implements ControllerInterface
{
    /** @var \TsukudolAPI\Application */
    private $app;

    public function __construct(\TsukudolAPI\Application $app)
    {
        $this->app = $app;
    }

    /**
     * GET /
     */
    public function index(Action $action)
    {
        return new TemplateResponse('index');
    }

    /**
     * GET /
     */
    public function license(Action $action)
    {
        $content = file_get_contents(__DIR__ . '/../../LICENSE');

        return new RawResponse($content, 'text/plain');
    }

    /**
     * 404 Not Found
     */
    public function display404(Action $action)
    {
        return new TemplateResponse('404', [], 404);
    }

    /**
     * 500 Internal Server Error
     */
    public function display500(Action $action)
    {
        return new TemplateResponse('500', [], 500);
    }
}
