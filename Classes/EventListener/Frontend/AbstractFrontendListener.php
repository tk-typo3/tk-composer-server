<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use TimonKreis\TkComposerServer\Event\FrontendRequestEvent;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Fluid\View\AbstractTemplateView;

/**
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
abstract class AbstractFrontendListener
{
    /**
     * @var FrontendRequestEvent
     */
    protected $frontendRequestEvent;

    /**
     * @param FrontendRequestEvent $frontendRequestEvent
     */
    public function __invoke(FrontendRequestEvent $frontendRequestEvent) : void
    {
        $this->frontendRequestEvent = $frontendRequestEvent;

        $this->execute();
    }

    /**
     * @param array $lines
     * @param int $statusCode
     */
    protected function setPlainResponse(array $lines = [], int $statusCode = 200) : void
    {
        $response = new Response();
        $response = $response->withHeader('Content-Type', 'text/plain');
        $response = $response->withStatus($statusCode);
        $response->getBody()->write(implode("\n", $lines));

        $this->frontendRequestEvent->setResponse($response);
    }

    /**
     * @param array $data
     * @param int $statusCode
     */
    protected function setJsonResponse(array $data = [], int $statusCode = 200) : void
    {
        $response = new JsonResponse($data);
        $response = $response->withStatus($statusCode);

        $this->frontendRequestEvent->setResponse($response);
    }

    /**
     * @param AbstractTemplateView $view
     * @param int $statusCode
     */
    protected function setHtmlResponse(AbstractTemplateView $view, int $statusCode = 200) : void
    {
        $response = new HtmlResponse($view->render());
        $response = $response->withStatus($statusCode);

        $this->frontendRequestEvent->setResponse($response);
    }

    /**
     *
     */
    abstract protected function execute() : void;
}
