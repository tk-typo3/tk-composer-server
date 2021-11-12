<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use Doctrine\DBAL\Driver\Exception;
use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Domain\Repository\RepositoryRepository;
use TimonKreis\TkComposerServer\Service\AccountService;

/**
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class RepositoryListener extends AbstractFrontendListener
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
     * @throws Exception|\Exception
     */
    protected function execute() : void
    {
        $pattern = '/^include\/' . Repository::PACKAGE_NAME_PATTERN . '\$[0-9a-f]{64}\.json$/';

        if (!preg_match($pattern, $this->frontendRequestEvent->getUri())) {
            return;
        }

        [$packageName, $checksum] = explode('$', substr($this->frontendRequestEvent->getUri(), 8, -5));

        /** @var Repository $repository */
        $repository = $this->repositoryRepository->findByPackageName($packageName);

        if (!$repository) {
            throw new \Exception(sprintf('Package "%s" does not exist.', $packageName), 1603305837);
        }

        if ($repository->getChecksum() !== $checksum) {
            throw new \Exception(sprintf('Invalid hash for package "%s".', $packageName), 1603305845);
        }

        if ($repository->getAccess() !== Repository::ACCESS_PUBLIC) {
            $account = $this->accountService->getAuthorizedAccount();

            if (!$account) {
                throw new \Exception(sprintf('Unable to access package "%s".', $packageName), 1603305970);
            }

            if ($repository->getAccess() === Repository::ACCESS_PRIVATE && !$account->getAllRepositories()) {
                $allowed = false;
                $repositories = $this->repositoryRepository->findByAccount($account);

                foreach ($repositories as $allowedRepository) {
                    if ($allowedRepository->getUid() === $repository->getUid()) {
                        $allowed = true;

                        break;
                    }
                }

                if (!$allowed) {
                    throw new \Exception(sprintf('Unable to access package "%s".', $packageName), 1603306135);
                }
            }
        }

        $this->setJsonResponse($repository->getData());
    }
}
