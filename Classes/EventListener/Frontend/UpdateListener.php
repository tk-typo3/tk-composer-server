<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Event\UpdateListener\UpdateEvent;
use TimonKreis\TkComposerServer\Service\ExtconfService;
use TimonKreis\TkComposerServer\Service\UpdateService;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class UpdateListener extends AbstractFrontendListener
{
    /**
     * @var UpdateService
     */
    protected $updateService;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param UpdateService $updateService
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(UpdateService $updateService, EventDispatcher $eventDispatcher)
    {
        $this->updateService = $updateService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *
     */
    protected function execute() : void
    {
        if (!ExtconfService::get('updateUri')
            || $this->frontendRequestEvent->getUri() !== ExtconfService::get('updateUri')
        ) {
            return;
        }

        set_time_limit(0);

        $errors = [];
        $updateEvent = GeneralUtility::makeInstance(UpdateEvent::class, $this->frontendRequestEvent->getRequest());

        $this->eventDispatcher->dispatch($updateEvent);

        if ($updateEvent->getRepository()) {
            try {
                $this->updateService->updateRepository($updateEvent->getRepository(), true);
            } catch (\Exception $e) {
                $errors[] = [
                    'repository' => $updateEvent->getRepository(),
                    'exception' => $e->getMessage(),
                ];
            }
        } else {
            $errors = $this->updateService->updateAllRepositories();
        }

        $data = [
            'status' => 'ok',
        ];

        if ($errors) {
            $data['status'] = 'error';
            $data['repositories'] = [];

            foreach ($errors as $error) {
                /** @var Repository $repository */
                $repository = $error['repository'];
                /** @var \Exception $exception */
                $exception = $error['exception'];

                $data['repositories'][$repository->getUrl()] = $exception->getMessage();
            }
        }

        $this->setJsonResponse($data, $errors ? 400 : 200);
    }
}
