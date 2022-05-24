<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend\Platforms;

use TimonKreis\TkComposerServer\Domain\Repository\RepositoryRepository;
use TimonKreis\TkComposerServer\Event\UpdateListener\UpdateEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package TimonKreis\TkComposerServer\EventListener\Frontend\Platforms
 */
abstract class AbstractPlatformListener
{
    /**
     * @var UpdateEvent
     */
    protected $updateEvent;

    /**
     * @var RepositoryRepository
     */
    protected $repositoryRepository;

    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @param UpdateEvent $updateEvent
     */
    public function __invoke(UpdateEvent $updateEvent) : void
    {
        $this->updateEvent = $updateEvent;

        $this->repositoryRepository = GeneralUtility::makeInstance(RepositoryRepository::class);

        try {
            $this->execute();
            $this->determineRepository();
        } catch (\Throwable $e) {}
    }

    /**
     * @noinspection PhpUnused
     * @return array
     */
    protected function getGet() : array
    {
        return $_GET ?? [];
    }

    /**
     * @noinspection PhpUnused
     * @return array
     */
    protected function getPost() : array
    {
        return $_POST ?? [];
    }

    /**
     * @noinspection PhpUnused
     * @return array
     */
    protected function getServer() : array
    {
        return $_SERVER ?? [];
    }

    /**
     * @noinspection PhpUnused
     * @return array
     */
    protected function getHeaders() : array
    {
        return getallheaders();
    }

    /**
     * @noinspection PhpUnused
     * @return array
     */
    protected function getBody() : array
    {
        static $body = null;

        if ($body === null) {
            $body = json_decode($this->updateEvent->getRequest()->getBody()->getContents(), true) ?? [];
        }

        return $body;
    }

    /**
     *
     */
    protected function determineRepository() : void
    {
        $urls = array_unique($this->urls);

        foreach ($urls as $url) {
            try {
                $repository = $this->repositoryRepository->findByUrl($url);

                if ($repository) {
                    $this->updateEvent->setRepository($repository);

                    return;
                }
            } catch (\Throwable $e) {}
        }
    }

    /**
     *
     */
    abstract protected function execute() : void;
}
