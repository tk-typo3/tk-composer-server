<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Event;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;

/**
 * @package TimonKreis\TkComposerServer\Event
 */
class FrontendRequestEvent
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var Response|null
     */
    protected $response;

    /**
     * @param ServerRequestInterface $request
     * @param string $uri
     */
    public function __construct(ServerRequestInterface $request, string $uri)
    {
        $this->request = $request;
        $this->uri = $uri;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest() : ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }

    /**
     * @return Response|null
     */
    public function getResponse() : ?Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response) : void
    {
        $this->response = $response;
    }
}
