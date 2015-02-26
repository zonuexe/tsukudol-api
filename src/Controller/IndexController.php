<?php
namespace TsukudolAPI\Controller;
use Teto\Routing\Action;
use TsukudolAPI\Response\TemplateResponse;
use TsukudolAPI\Response\RawResponse;

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
        $this->app->setHttpStatus(404);

        return new TemplateResponse('404');
    }

    /**
     * 500 Internal Server Error
     */
    public function display500(Action $action)
    {
        $this->app->setHttpStatus(500);

        return new TemplateResponse('500');
    }
}
