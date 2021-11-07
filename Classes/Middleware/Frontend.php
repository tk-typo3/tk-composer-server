<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TimonKreis\TkComposerServer\Event\FrontendRequestEvent;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package TimonKreis\TkComposerServer\Middleware
 */
class Frontend implements MiddlewareInterface
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $requestUri = rawurldecode(ltrim($request->getAttribute('normalizedParams')->getRequestUri(), '/'));
        $requestUri = strpos($requestUri, '?') !== false ? strstr($requestUri, '?', true) : $requestUri;

        $requestEvent = GeneralUtility::makeInstance(FrontendRequestEvent::class, $request, $requestUri);

        try {
            $this->eventDispatcher->dispatch($requestEvent);

            if (!$requestEvent->getResponse()) {
                throw new \Exception('Invalid frontend request.');
            }
        } catch (\Exception $e) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'text/plain');
            $response = $response->withStatus(400);
            $response->getBody()->write($e->getMessage());

            $requestEvent->setResponse($response);
        }

        return $requestEvent->getResponse();
    }
}
