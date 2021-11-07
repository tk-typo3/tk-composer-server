<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use TimonKreis\TkComposerServer\Domain\Repository\RepositoryRepository;
use TimonKreis\TkComposerServer\Service\AccountService;

/**
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class PackagesJsonListener extends AbstractFrontendListener
{
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var RepositoryRepository
     */
    protected $repositoryRepository;

    /**
     * @param AccountService $accountService
     * @param RepositoryRepository $repositoryRepository
     */
    public function __construct(AccountService $accountService, RepositoryRepository $repositoryRepository)
    {
        $this->accountService = $accountService;
        $this->repositoryRepository = $repositoryRepository;
    }

    /**
     *
     */
    protected function execute() : void
    {
        if ($this->frontendRequestEvent->getUri() != 'packages.json') {
            return;
        }

        $data = [
            'packages' => [],
            'includes' => [],
        ];

        $account = $this->accountService->getAuthorizedAccount();
        $repositories = $this->repositoryRepository->findByAccount($account);

        foreach ($repositories as $repository) {
            $include = sprintf('include/%s$%s.json', $repository->getPackageName(), $repository->getChecksum());

            $data['includes'][$include] = [
                'sha256' => $repository->getChecksum(),
            ];
        }

        $this->setJsonResponse($data);
    }
}
