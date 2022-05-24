<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Event\UpdateListener;

use Psr\Http\Message\ServerRequestInterface;
use TimonKreis\TkComposerServer\Domain\Model\Repository;

/**
 * @package TimonKreis\TkComposerServer\Event\UpdateListener
 */
class UpdateEvent
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var Repository|null
     */
    protected $repository;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest() : ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return Repository|null
     */
    public function getRepository() : ?Repository
    {
        return $this->repository;
    }

    /**
     * @param Repository $repository
     */
    public function setRepository(Repository $repository) : void
    {
        $this->repository = $repository;
    }
}
