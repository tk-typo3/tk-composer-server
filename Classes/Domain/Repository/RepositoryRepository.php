<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Domain\Repository;

use Doctrine\DBAL\ParameterType;
use TimonKreis\TkComposerServer\Domain\Model\Account;
use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Domain\Model\RepositoryGroup;

/**
 * @package TimonKreis\TkComposerServer\Domain\Repository
 */
class RepositoryRepository extends AbstractRepository
{
    public const TABLE = 'tx_tkcomposerserver_domain_model_repository';

    /**
     * Find packages by account
     *
     * @param Account|null $account
     * @return Repository[]
     */
    public function findByAccount(Account $account = null) : array
    {
        $queryBuilder = $this->getQueryBuilder();
        $query = $queryBuilder->select('*');

        if ($account) {
            if (!$account->getAllRepositories()) {
                $query->where(
                    $queryBuilder->expr()->eq(
                        'access',
                        $queryBuilder->createNamedParameter(Repository::ACCESS_PROTECTED, ParameterType::INTEGER)
                    )
                )->orWhere(
                    $queryBuilder->expr()->eq(
                        'access',
                        $queryBuilder->createNamedParameter(Repository::ACCESS_PUBLIC, ParameterType::INTEGER)
                    )
                );

                $repositoryUids = [];

                /** @var RepositoryGroup $repositoryGroup */
                foreach ($account->getRepositoryGroups() as $repositoryGroup) {
                    /** @var Repository $repository */
                    foreach ($repositoryGroup->getRepositories() as $repository) {
                        $repositoryUids[$repository->getUid()]
                            = $queryBuilder->createNamedParameter($repository->getUid(), ParameterType::INTEGER);
                    }
                }

                /** @var Repository $repository */
                foreach ($account->getRepositories() as $repository) {
                    $repositoryUids[$repository->getUid()]
                        = $queryBuilder->createNamedParameter($repository->getUid(), ParameterType::INTEGER);
                }

                if ($repositoryUids) {
                    $query->orWhere($queryBuilder->expr()->in('uid', array_values($repositoryUids)));
                }
            }
        } else {
            $query->where(
                $queryBuilder->expr()->eq(
                    'access',
                    $queryBuilder->createNamedParameter(Repository::ACCESS_PUBLIC, ParameterType::INTEGER)
                )
            );
        }

        return $this->getMappedData(Repository::class, $query->execute()->fetchAllAssociative());
    }
}
