<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Hooks;

use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Domain\Repository\RepositoryRepository;
use TimonKreis\TkComposerServer\Service\UpdateService;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package TimonKreis\TkComposerServer\Hooks
 */
class UpdateHook
{
    /**
     * @noinspection PhpUnusedParameterInspection
     * @param string $action
     * @param string $table
     * @param int|string $uid
     * @param array $fields
     * @param DataHandler $dataHandler
     */
    public function processDatamap_afterDatabaseOperations(
        string $action,
        string $table,
        $uid,
        array $fields,
        DataHandler $dataHandler
    ) : void {
        if ($table == RepositoryRepository::TABLE && $action == 'new') {
            /** @var RepositoryRepository $repositoryRepository */
            $repositoryRepository = GeneralUtility::makeInstance(RepositoryRepository::class);
            /** @var Repository $repository */
            $repository = $repositoryRepository->findByUid($dataHandler->substNEWwithIDs[$uid]);

            try {
                /** @var UpdateService $updateService */
                $updateService = GeneralUtility::makeInstance(UpdateService::class);
                $updateService->updateRepository($repository);
            } catch (\Exception $e) {
                /** @var FlashMessage $message */
                $message = GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $e->getMessage(),
                    '',
                    FlashMessage::ERROR,
                    false
                );

                /** @var FlashMessageService $messageService */
                $messageService = GeneralUtility::makeInstance(FlashMessageService::class);
                $messageQueue = $messageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
            }
        }
    }
}
